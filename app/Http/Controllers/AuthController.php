<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Plant;
use App\Models\PemeriksaanKebersihanArea;
use App\Models\InputArea;
use App\Models\InputMasterForm;
use App\Models\InputMasterFormField;
use App\Models\DetailKomplain;
use App\Models\Produk;
use App\Models\PemeriksaanLoadingKendaraan;
use App\Models\PemeriksaanSuhuRuang;
use App\Models\PemeriksaanSuhuRuangV2;
use App\Models\PemeriksaanSuhuRuangV3;
use Carbon\Carbon;
class AuthController extends Controller
{
    /**
     * Show the login form — redirect ke PDQC portal login jika SSO dikonfigurasi.
     * Jika EMPLOYEE_PORTAL_URL tidak diset, fallback ke halaman login lokal.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $bypassKey = $request->query('access_key');
        $expectedKey = config('services.login_bypass.key') ?? config('services.employee_api.login_bypass.key');

        if ($bypassKey && $expectedKey && hash_equals((string)$expectedKey, (string)$bypassKey)) {
            return view('auth.login');
        }

        $portalLoginUrl = config('services.employee_api.portal_login_url');
        if (!empty($portalLoginUrl)) {
            return redirect($portalLoginUrl);
        }

        $portalUrl = config('services.employee_api.portal_url');
        if (!empty($portalUrl)) {
            return redirect(rtrim($portalUrl, '/') . '/login');
        }

        // Fallback: tampilkan form login lokal
        return view('auth.login');
    }

    /**
     * Show local login form — akses tersembunyi untuk Superadmin.
     * Dapat diakses via /local-login jika SSO/central sedang bermasalah.
     */
    public function showLocalLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->onlyInput('username');
        }

        // Check password manually
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Password salah.',
            ])->onlyInput('username');
        }
         if (is_null($user->email_verified_at)) {
            return back()->withErrors([
                'username' => 'Akun belum diaktivasi. Silakan hubungi admin kepegawaian untuk mengaktivasi akun Anda.',
            ])->onlyInput('username');
        }

        // Login user manually
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        
        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            try {
                \Illuminate\Support\Facades\Http::withToken(config('services.employee_api.sso_secret'))
                    ->timeout(5)
                    ->post(config('services.employee_api.url') . '/sso/report-logout', [
                        'user_uuid'    => $user->uuid,
                        'project_uuid' => config('services.employee_api.this_project_uuid'),
                    ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('SSO: Laporan logout ke central gagal', ['error' => $e->getMessage()]);
            }
        }

        $portalUrl = config('services.employee_api.portal_url');
        if (!empty($portalUrl)) {
            return redirect($portalUrl);
        }

        return redirect('/login')->with('success', 'Logout berhasil!');
    }

    /**
     * Show dashboard (protected route)
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $dashboardUrl = config('services.grafana_qc.dashboard_url');

        abort_unless($dashboardUrl, 503, 'URL dashboard Grafana belum dikonfigurasi.');

        $roleName = strtolower((string) ($user->role->role ?? ''));
        $isSuperAdmin = $roleName === 'superadmin';
        $isManager = $user->isManager();
        $canChoosePlant = $isSuperAdmin || $isManager;

        if ($isSuperAdmin) {
            $plants = Plant::query()->orderBy('plant')->get(['id', 'uuid', 'plant']);
            $requestedPlant = (string) $request->query('plant', '$__all');
            $plantExists = $requestedPlant === '$__all'
                || $plants->contains(fn ($plant) => (string) $plant->uuid === $requestedPlant);
            $selectedPlant = $requestedPlant === '$__all'
                ? '$__all'
                : ($plantExists ? (string) $plants->firstWhere('uuid', $requestedPlant)->id : '$__all');
            $selectedPlantUuid = $selectedPlant === '$__all'
                ? '$__all'
                : (string) $plants->firstWhere('id', (int) $selectedPlant)->uuid;
        } elseif ($isManager) {
            $plants = $user->allowedPlants()->orderBy('plant')->get(['plants.id', 'plants.uuid', 'plants.plant']);
            $requestedPlant = (string) $request->query('plant', '');
            $selectedPlantRecord = $plants->firstWhere('uuid', $requestedPlant)
                ?? $plants->firstWhere('id', $user->getEffectivePlantId())
                ?? $plants->first();
            $selectedPlant = (string) ($selectedPlantRecord?->id ?? '0');
            $selectedPlantUuid = (string) ($selectedPlantRecord?->uuid ?? '');
        } else {
            $plantId = $user->getEffectivePlantId();
            $plants = $plantId ? Plant::whereKey($plantId)->get(['id', 'uuid', 'plant']) : collect();
            $selectedPlant = (string) ($plantId ?? '0');
            $selectedPlantUuid = (string) ($plants->first()?->uuid ?? '');
        }

        $fromInput = trim((string) $request->query('from', ''));
        $toInput = trim((string) $request->query('to', ''));
        $now = now();
        try {
            $from = ($fromInput !== '' ? Carbon::parse($fromInput) : $now->copy()->subHours(24))->timestamp * 1000;
            $to = ($toInput !== '' ? Carbon::parse($toInput) : $now)->timestamp * 1000;
        } catch (\Throwable $exception) {
            $fromInput = '';
            $toInput = '';
            $from = $now->copy()->subHours(24)->timestamp * 1000;
            $to = $now->timestamp * 1000;
        }

        if ($from > $to) {
            $fromInput = '';
            $toInput = '';
            $from = $now->copy()->subHours(24)->timestamp * 1000;
            $to = $now->timestamp * 1000;
        }

        // Ringkasan sanitasi mengikuti plant dan rentang tanggal dashboard.
        $fromDate = Carbon::createFromTimestamp((int) floor($from / 1000))->toDateString();
        $toDate = Carbon::createFromTimestamp((int) floor($to / 1000))->toDateString();
        $sanitationFromDate = $fromDate;
        $sanitationToDate = $toDate;
        $sanitationQuery = PemeriksaanKebersihanArea::query()
            ->where('is_audit', false)
            ->whereBetween('tanggal', [$fromDate, $toDate]);

        if ($selectedPlant !== '$__all') {
            $sanitationQuery->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }

        $areaNames = InputArea::query()->pluck('nama_area', 'id');
        $masterFormNames = InputMasterForm::query()->pluck('nama_form', 'id');
        $fieldNames = InputMasterFormField::query()->pluck('field_name', 'id');
        $fieldNamesByForm = InputMasterFormField::query()
            ->orderBy('field_order')
            ->get(['id_master_form', 'field_name'])
            ->groupBy('id_master_form')
            ->map(fn ($fields) => $fields->pluck('field_name')->values());
        $sanitationRecords = $sanitationQuery->get(['id', 'area_data']);
        $sanitationTotals = ['inspection' => 0, 'nc' => 0, 'ok' => 0];
        $sanitationByArea = [];
        $sanitationByForm = [];
        $sanitationByField = [];
        $sanitationNcDetails = [];

        foreach ($sanitationRecords as $record) {
            $entries = is_array($record->area_data) ? $record->area_data : [];
            foreach ($entries as $entry) {
                $areaName = $areaNames->get($entry['id_area'] ?? null, 'Area tidak diketahui');
                $formName = $masterFormNames->get($entry['id_master_form'] ?? null, 'Form tidak diketahui');
                foreach (($entry['fields'] ?? []) as $fieldIndex => $field) {
                    // Verifikasi adalah hasil akhir; untuk data lama yang belum menyimpan
                    // verifikasi, gunakan hasil sesudah proses lalu hasil sebelum proses.
                    $status = $field['verifikasi_hasil']
                        ?? $field['status_saat_proses']
                        ?? $field['status_sebelum_proses']
                        ?? $field['status']
                        ?? null;
                    if ($status === null || $status === '') {
                        continue;
                    }

                    $isOk = (string) $status === '1';
                    $fieldId = $field['id_master_form_field'] ?? $field['field_id'] ?? null;
                    $formFieldNames = $fieldNamesByForm->get((string) ($entry['id_master_form'] ?? ''))
                        ?? $fieldNamesByForm->get($entry['id_master_form'] ?? null);
                    $fieldName = $field['field_name'] ?? $field['nama_field']
                        ?? $fieldNames->get($fieldId)
                        ?? $formFieldNames?->get($fieldIndex)
                        ?? 'Nama aspek tidak tersedia';
                    $sanitationTotals['inspection']++;
                    $sanitationTotals[$isOk ? 'ok' : 'nc']++;

                    $increment = static function (&$bucket) use ($isOk): void {
                        $bucket ??= ['total' => 0, 'nc' => 0];
                        $bucket['total']++;
                        $bucket['nc'] += $isOk ? 0 : 1;
                    };
                    $increment($sanitationByArea[$areaName]);
                    $increment($sanitationByForm[$formName]);
                    $increment($sanitationByField[$fieldName]);

                    $key = $areaName . '|' . $formName . '|' . $fieldName;
                    $sanitationNcDetails[$key] ??= [
                        'area' => $areaName,
                        'form' => $formName,
                        'field' => $fieldName,
                        'nc' => 0,
                        'total' => 0,
                    ];
                    $sanitationNcDetails[$key]['total']++;
                    $sanitationNcDetails[$key]['nc'] += $isOk ? 0 : 1;
                }
            }
        }

        $topSanitation = static function (array $items): array {
            $rows = [];
            foreach ($items as $name => $counts) {
                $rows[] = ['name' => $name, 'total' => $counts['total'], 'nc' => $counts['nc']];
            }
            usort($rows, fn ($a, $b) => $b['total'] <=> $a['total']);
            return array_slice($rows, 0, 3);
        };
        $sanitationTopAreas = $topSanitation($sanitationByArea);
        $sanitationTopForms = $topSanitation($sanitationByForm);
        $sanitationTopFields = $topSanitation($sanitationByField);
        $sanitationNcRows = array_values(array_filter($sanitationNcDetails, fn ($row) => $row['nc'] > 0));
        usort($sanitationNcRows, fn ($a, $b) => $b['nc'] <=> $a['nc']);
        $sanitationNcRows = array_slice($sanitationNcRows, 0, 10);

        // Top issue bersumber dari setiap baris produk pada form Detail Komplain.
        $complaintQuery = DetailKomplain::query()
            ->whereBetween('tanggal_kedatangan', [$fromDate, $toDate]);
        if ($selectedPlant !== '$__all') {
            $complaintQuery->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }
        $complaintRecords = $complaintQuery
            ->orderByDesc('tanggal_kedatangan')
            ->orderByDesc('id')
            ->get();

        $allProductIds = $complaintRecords
            ->flatMap(fn ($record) => (array) $record->id_produk_array)
            ->filter(fn ($id) => $id !== null && $id !== '')
            ->unique()
            ->values();
        $complaintProductNames = $allProductIds->isNotEmpty()
            ? Produk::whereIn('id', $allProductIds)->pluck('nama_produk', 'id')
            : collect();
        $topComplaintRows = [];

        foreach ($complaintRecords as $complaint) {
            $productIds = array_values((array) $complaint->id_produk_array);
            $productNames = array_values((array) $complaint->nama_produk_array);
            $productionCodes = array_values((array) $complaint->kode_produksi_array);
            $rejectedQuantities = array_values((array) $complaint->jumlah_di_tolak_array);
            $descriptions = array_values((array) $complaint->keterangan_array);
            $rowCount = max(1, count($productIds), count($productNames), count($productionCodes), count($rejectedQuantities), count($descriptions));

            for ($index = 0; $index < $rowCount; $index++) {
                $description = trim((string) ($descriptions[$index] ?? $complaint->keterangan ?? ''));
                if ($description === '') {
                    continue;
                }

                $productId = $productIds[$index] ?? null;
                $topComplaintRows[] = [
                    'uuid' => $complaint->uuid,
                    'date' => $complaint->tanggal_kedatangan,
                    'product' => $productNames[$index]
                        ?? $complaintProductNames->get($productId)
                        ?? $complaint->nama_produk
                        ?? '-',
                    'rejected' => $rejectedQuantities[$index] ?? $complaint->jumlah_di_tolak ?? '-',
                    'production_code' => $productionCodes[$index] ?? $complaint->kode_produksi ?? '-',
                    'description' => $description,
                ];
            }
        }
        $topComplaintRows = array_slice($topComplaintRows, 0, 10);

        // Top issue precooling: standar suhu adalah <= 0°C.
        $precoolingQuery = PemeriksaanLoadingKendaraan::query()
            ->with(['kendaraan', 'ekspedisi', 'tujuanPengiriman'])
            ->where('is_audit', false)
            ->whereBetween('tanggal', [$fromDate, $toDate]);
        if ($selectedPlant !== '$__all') {
            $precoolingQuery->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }

        $topPrecoolingRows = [];
        foreach ($precoolingQuery->orderByDesc('tanggal')->orderByDesc('id')->get() as $inspection) {
            $rawTemperature = trim((string) $inspection->suhu_precooling);
            if ($rawTemperature === '') {
                continue;
            }

            // Terima format seperti -1,5°C, 2 C, atau 0.
            $temperature = preg_replace('/[^0-9,.+\\-]/u', '', $rawTemperature);
            if (str_contains($temperature, ',') && !str_contains($temperature, '.')) {
                $temperature = str_replace(',', '.', $temperature);
            } else {
                $temperature = str_replace(',', '', $temperature);
            }
            $isStandard = is_numeric($temperature) && (float) $temperature <= 0;
            if ($isStandard) {
                continue;
            }

            $vehicle = $inspection->kendaraan;
            $topPrecoolingRows[] = [
                'date' => $inspection->tanggal,
                'temperature' => $rawTemperature,
                'vehicle_type' => $inspection->jenis_kendaraan_manual
                    ?: ($vehicle->jenis_kendaraan ?? '-'),
                'vehicle_number' => $inspection->no_kendaraan_manual
                    ?: ($vehicle->no_kendaraan ?? '-'),
                'expedition' => $inspection->ekspedisi->nama_ekspedisi ?? '-',
                'destination' => $inspection->tujuanPengiriman->nama_tujuan
                    ?? $inspection->nama_tujuan_manual
                    ?? '-',
                'description' => trim((string) $inspection->keterangan) ?: '-',
            ];
        }
        $topPrecoolingRows = array_slice($topPrecoolingRows, 0, 10);

        // Top issue suhu ruang memakai nilai awal pada tabel pemeriksaan utama,
        // bukan nilai perubahan yang tersimpan pada tabel histories.
        $parseTemperature = static function ($value): ?float {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }
            $normalized = preg_replace('/[^0-9,.+\\-]/u', '', $value);
            if (str_contains($normalized, ',') && !str_contains($normalized, '.')) {
                $normalized = str_replace(',', '.', $normalized);
            } else {
                $normalized = str_replace(',', '', $normalized);
            }
            return is_numeric($normalized) ? (float) $normalized : null;
        };
        $topRoomTemperatureRows = [];
        $addRoomTemperatureIssue = static function (
            $record,
            string $form,
            string $area,
            $actual,
            string $standard,
            callable $isWithinStandard
        ) use (&$topRoomTemperatureRows, $parseTemperature): void {
            $actualText = trim((string) $actual);
            if ($actualText === '') {
                return;
            }

            $numericActual = $parseTemperature($actualText);
            if ($numericActual !== null && $isWithinStandard($numericActual)) {
                return;
            }

            $topRoomTemperatureRows[] = [
                'date' => $record->tanggal,
                'form' => $form,
                'area' => $area,
                'actual' => $actualText,
                'standard' => $standard,
                'time' => $record->pukul ?? '-',
                'note' => trim((string) ($record->keterangan ?? '')) ?: '-',
            ];
        };

        $v1Query = PemeriksaanSuhuRuang::query()
            ->where('is_audit', false)
            ->whereBetween('tanggal', [$fromDate, $toDate]);
        if ($selectedPlant !== '$__all') {
            $v1Query->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }
        foreach ($v1Query->get() as $record) {
            $data = is_array($record->suhu_data)
                ? $record->suhu_data
                : (json_decode((string) $record->suhu_data, true) ?: []);

            foreach ((array) ($data['cold_storage'] ?? []) as $unit => $reading) {
                if (is_array($reading)) {
                    $unitName = $reading['unit'] ?? $unit;
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V1', 'Cold Storage ' . $unitName,
                        $reading['actual'] ?? null, '≤ -18°C', fn ($value) => $value <= -18);
                }
            }
            foreach ((array) ($data['anteroom_loading'] ?? []) as $unit => $reading) {
                if (is_array($reading)) {
                    $unitName = $reading['unit'] ?? $unit;
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V1', 'Anteroom Loading ' . $unitName,
                        $reading['actual'] ?? null, '-5 sampai 5°C', fn ($value) => $value >= -5 && $value <= 5);
                }
            }
            foreach ([
                'pre_loading' => 'Pre Loading',
                'prestaging' => 'Prestaging',
                'anteroom_ekspansi_further' => 'Ekspansi Further',
                'anteroom_ekspansi_sausage' => 'Ekspansi Sausage',
            ] as $section => $label) {
                $reading = $data[$section] ?? [];
                if (is_array($reading)) {
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V1', $label,
                        $reading['actual'] ?? null, '-5 sampai 5°C', fn ($value) => $value >= -5 && $value <= 5);
                }
            }
        }

        $v2Query = PemeriksaanSuhuRuangV2::query()
            ->where('is_audit', false)
            ->whereBetween('tanggal', [$fromDate, $toDate]);
        if ($selectedPlant !== '$__all') {
            $v2Query->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }
        foreach ($v2Query->get() as $record) {
            foreach ([
                'cold_storage' => ['Cold Storage', '<= -18°C', fn ($value) => $value <= -18],
                'anteroom_loading' => ['Anteroom Loading', '-5 sampai 5°C', fn ($value) => $value >= -5 && $value <= 5],
            ] as $section => [$label, $standard, $check]) {
                $readings = $record->{'suhu_' . $section};
                foreach ((array) $readings as $unit => $reading) {
                    if (is_array($reading)) {
                        $unitName = $reading['unit'] ?? $unit;
                        $addRoomTemperatureIssue($record, 'Suhu Ruang V2', $label . ' ' . $unitName,
                            $reading['actual'] ?? null, $standard, $check);
                    }
                }
            }

            foreach ([
                'pre_loading' => 'Pre Loading',
                'prestaging' => 'Prestaging',
                'anteroom_ekspansi_abf' => 'Ekspansi ABF',
            ] as $section => $label) {
                $reading = $record->{'suhu_' . $section};
                if (is_array($reading)) {
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V2', $label,
                        $reading['actual'] ?? null, '-5 sampai 5°C', fn ($value) => $value >= -5 && $value <= 5);
                }
            }

            foreach ([
                'chillroom_rm' => 'Chillroom RM',
                'chillroom_domestik' => 'Chillroom Domestik',
            ] as $section => $label) {
                $reading = $record->{'suhu_' . $section};
                if (is_array($reading)) {
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V2', $label,
                        $reading['actual'] ?? null, '0°C sampai 4°C', fn ($value) => $value >= 0 && $value <= 4);
                }
            }
        }

        $v3Query = PemeriksaanSuhuRuangV3::query()
            ->where('is_audit', false)
            ->whereBetween('tanggal', [$fromDate, $toDate]);
        if ($selectedPlant !== '$__all') {
            $v3Query->whereHas('user', fn ($query) => $query->where('id_plant', $selectedPlant));
        }
        $v3Standards = [
            'premix' => ['Premix', 15, 25],
            'seasoning' => ['Seasoning', 20, 30],
            'cassing' => ['Casing', 15, 20],
            'dry' => ['Dry', 25, 35],
        ];
        foreach ($v3Query->get() as $record) {
            foreach ($v3Standards as $section => [$label, $minimum, $maximum]) {
                $readings = $record->{'suhu_' . $section};
                foreach ((array) $readings as $unit => $reading) {
                    if (!is_array($reading)) {
                        continue;
                    }
                    $unitName = str_replace('unit_', '', (string) ($reading['unit'] ?? $unit));
                    $addRoomTemperatureIssue($record, 'Suhu Ruang V3', $label . ' Unit ' . $unitName,
                        $reading['actual'] ?? null, $minimum . ' sampai ' . $maximum . '°C',
                        fn ($value) => $value >= $minimum && $value <= $maximum);
                }
            }
        }
        usort($topRoomTemperatureRows, fn ($a, $b) => strcmp((string) $b['date'], (string) $a['date']));
        $topRoomTemperatureRows = array_slice($topRoomTemperatureRows, 0, 20);

        $panelIds = range(1, 9);
        $panelNames = [
            1 => 'Incoming Material',
            2 => 'Loading Produk dan Kendaraan',
            3 => 'Return Produk Customer',
            4 => 'Cold Storage Food Processing',
            5 => 'CS Meat',
            6 => 'Gudang Dry',
            7 => 'Trend Suhu Food Processing',
            8 => 'Trend Suhu CS Meat',
            9 => 'Trend Suhu Gudang Dry',
        ];

        $soloUrl = preg_replace('~/d/~', '/d-solo/', $dashboardUrl, 1);
        $panelUrls = [];
        foreach ($panelIds as $panelId) {
            $panelUrls[$panelId] = rtrim($soloUrl, '?&') . '?' . http_build_query([
                'orgId' => 1,
                'panelId' => $panelId,
                'from' => $from,
                'to' => $to,
                'timezone' => 'browser',
                'var-id_plant' => $selectedPlant,
                'kiosk' => 'tv',
                'theme' => 'dark',
            ]);
        }

        $grafanaDashboardUrl = rtrim($dashboardUrl, '?&') . '?' . http_build_query([
            'orgId' => 1,
            'from' => $from,
            'to' => $to,
            'timezone' => 'browser',
            'var-id_plant' => $selectedPlant,
        ]);

        return view('dashboard', compact(
            'plants',
            'selectedPlant',
            'selectedPlantUuid',
            'canChoosePlant',
            'isSuperAdmin',
            'isManager',
            'fromInput',
            'toInput',
            'panelIds',
            'panelNames',
            'panelUrls',
            'grafanaDashboardUrl',
            'sanitationTotals',
            'sanitationFromDate',
            'sanitationToDate',
            'sanitationTopAreas',
            'sanitationTopForms',
            'sanitationTopFields',
            'sanitationNcRows',
            'topComplaintRows',
            'topPrecoolingRows',
            'topRoomTemperatureRows'
        ));
    }
    public function check(): JsonResponse
    {
        try {
            DB::connection()->getPdo();


            return response()->json([
                'status' => 'ok',
                'message' => 'MySQL connection is alive',
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'MySQL connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

}
