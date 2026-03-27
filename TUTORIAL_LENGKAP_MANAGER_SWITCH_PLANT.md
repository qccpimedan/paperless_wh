# Tutorial Ultimate: Membangun Fitur Manager Multi-Plant Access (Switch Plant)

Tutorial ini adalah panduan lengkap dari awal (Database) sampai akhir (Frontend) untuk fitur **Switch Plant** bagi role **Manager**.

---

## Langkah 1: Persiapan Database

Kita butuh tempat untuk menyimpan plant yang aktif dan hak akses manager.

### 1.1 Migrasi Kolom `active_plant_id` di Tabel `users`
File: `database/migrations/2026_03_18_024342_add_active_plant_id_to_users_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('active_plant_id')->nullable()->after('id_plant');
            $table->foreign('active_plant_id')->references('id')->on('plants')->onDelete('set null');
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_plant_id']);
            $table->dropColumn('active_plant_id');
        });
    }
};
```

### 1.2 Migrasi Tabel Pivot `manager_plants`
File: `database/migrations/2026_03_18_030649_create_manager_plants_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('manager_plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('manager_plants');
    }
};
```

---

## Langkah 2: Logika Master pada Model (`app/Models/User.php`)

Ini adalah isi lengkap method yang perlu ditambahkan ke dalam class `User`:

```php
// Pastikan 'active_plant_id' sudah masuk ke dalam array $fillable
protected $fillable = [
    // ... field lainnya ...
    'active_plant_id',
];

/**
 * RELASI: Ke Plant yang sedang dipilih saat switch
 */
public function activePlant()
{
    return $this->belongsTo(Plant::class, 'active_plant_id');
}

/**
 * RELASI: Daftar Plant yang di-assign oleh Superadmin ke Manager ini
 */
public function allowedPlants()
{
    return $this->belongsToMany(Plant::class, 'manager_plants', 'user_id', 'plant_id')
                ->withTimestamps();
}

/**
 * LOGIKA: Cek apakah user memiliki role Manager
 */
public function isManager(): bool
{
    $roleSlug = $this->role ? strtolower($this->role->role) : null;
    return $roleSlug === 'manager';
}

/**
 * KEAMANAN: Verifikasi akses Manager ke plant tertentu
 */
public function canAccessPlant(int $plantId): bool
{
    if (!$this->isManager()) return false;
    return $this->allowedPlants()->where('plants.id', $plantId)->exists();
}

/**
 * LOGIKA: Mendapatkan objek Plant efektif (untuk tampilan)
 */
public function getEffectivePlant(): ?Plant
{
    if ($this->isManager() && $this->active_plant_id) {
        return $this->activePlant;
    }
    return $this->plant;
}

/**
 * LOGIKA: Mendapatkan ID Plant efektif (untuk query filter)
 */
public function getEffectivePlantId(): ?int
{
    if ($this->isManager() && $this->active_plant_id) {
        return (int) $this->active_plant_id;
    }
    return (int) $this->id_plant;
}
```

---

## Langkah 3: Setup Helper & Trait

### 3.1 Global Helper (`app/helpers.php`)
Berguna untuk mengakses ID plant aktif di mana saja (termasuk view) tanpa perlu panggil model.
```php
function effective_plant_id($user = null): ?int {
    $user = $user ?? Auth::user();
    if (!$user) return null;
    return $user->getEffectivePlantId();
}
```

### 3.2 Trait Controller (`app/Traits/HasEffectivePlant.php`)
Campuran fungsi yang bisa di-inject ke berbagai controller.
```php
<?php

namespace App\Traits;

use App\Models\Plant;
use Illuminate\Support\Facades\Auth;

trait HasEffectivePlant
{
    protected function getActivePlantId($user = null): ?int
    {
        $user = $user ?? Auth::user();
        return $user ? $user->getEffectivePlantId() : null;
    }

    protected function getActivePlant($user = null): ?Plant
    {
        $user = $user ?? Auth::user();
        return $user ? $user->getEffectivePlant() : null;
    }

    protected function isManagerUser($user = null): bool
    {
        $user = $user ?? Auth::user();
        return $user ? $user->isManager() : false;
    }
}
```

---

## Langkah 4: Routing (`routes/web.php`)

```php
Route::middleware(['auth'])->group(function () {
    // Switch & Reset Plant
    Route::post('manager/switch-plant', [ManagerController::class, 'switchPlant'])->name('manager.switch-plant');
    Route::post('manager/reset-plant', [ManagerController::class, 'resetPlant'])->name('manager.reset-plant');
    
    // Assign Access (Superadmin)
    Route::get('users/{user}/assign-plants', [UserController::class, 'assignPlants'])->name('users.assign-plants');
    Route::post('users/{user}/assign-plants', [UserController::class, 'saveAssignPlants'])->name('users.save-assign-plants');
});
```

---

## Langkah 5: Manager Controller UTUH (`app/Http/Controllers/ManagerController.php`)

Menangani logika perpindahan (switch) dan pengembalian (reset) plant.

```php
// ... namespace & imports ...
class ManagerController extends Controller
{
    public function switchPlant(Request $request)
    {
        $user = Auth::user();
        if (!$user->isManager()) abort(403);

        $request->validate(['plant_id' => 'required|exists:plants,id']);
        $plantId = (int) $request->plant_id;

        if (!$user->canAccessPlant($plantId)) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $plant = Plant::findOrFail($plantId);
        $user->update(['active_plant_id' => $plant->id]);
        return redirect()->back()->with('success', "Pindah ke {$plant->plant}");
    }

    public function resetPlant(Request $request)
    {
        $user = Auth::user();
        if (!$user->isManager()) abort(403);
        $user->update(['active_plant_id' => null]);
        return redirect()->back()->with('success', "Kembali ke Plant Asal.");
    }
}
```

---

## Langkah 6: Contoh Query Filter (Kontroler Halaman)

Data otomatis memfilter sesuai plant yang sedang aktif:

```php
$plantId = Auth::user()->getEffectivePlantId();

$query->whereHas('user', function ($q) use ($plantId) {
    $q->where('id_plant', $plantId);
});
```

---

## Langkah 7: Antarmuka UI (Navbar & Sidebar)

### 7.1 Dropdown Navbar
Menampilkan daftar `allowedPlants` milik Manager.

### 7.2 Sidebar Dinamis
Menampilkan badge "Switched" jika `active_plant_id` tidak null.
