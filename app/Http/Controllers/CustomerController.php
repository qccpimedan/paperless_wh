<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $customers = Customer::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $customers = Customer::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-cust.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-cust.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cust' => 'required|array|min:1',
            'nama_cust.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaCust = array_filter($request->nama_cust, function($value) {            
            return !empty(trim($value));
        });

        if (empty($namaCust)) {
            return back()->withErrors(['nama_cust' => 'Minimal harus ada satu nama customer.']);        }

        // Create separate record for each nama Cust
        foreach ($namaCust as $nama) {
            Customer::create([
                'id_user' => Auth::id(),
                'nama_cust' => trim($nama),
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Check access based on plant
        $this->checkPlantAccess($customer);
        
        $customer->load('user');
        return view('super-admin.input-cust.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        // Check access based on plant
        $this->checkPlantAccess($customer);
        
        return view('super-admin.input-cust.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Check access based on plant
        $this->checkPlantAccess($customer);
        
        $request->validate([
            'nama_cust' => 'required|string|max:255',
        ]);

        $customer->update([
            'nama_cust' => trim($request->nama_cust),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Check access based on plant
        $this->checkPlantAccess($customer);
        
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus!');
    }

    /**
     * Check if user has access to customer based on plant
     */
    private function checkPlantAccess(Customer $customer)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($customer->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
