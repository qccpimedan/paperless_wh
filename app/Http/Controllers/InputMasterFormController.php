<?php

namespace App\Http\Controllers;

use App\Models\InputMasterForm;
use App\Models\InputMasterFormField;
use App\Models\InputArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputMasterFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $masterForms = InputMasterForm::with(['user.role', 'user.plant', 'area'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $masterForms = InputMasterForm::with(['user.role', 'user.plant', 'area'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-master-form.index', compact('masterForms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get areas based on user plant
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $areas = InputArea::orderBy('nama_area')->get();
        } else {
            $areas = InputArea::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->orderBy('nama_area')->get();
        }
        
        return view('super-admin.input-master-form.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_form' => 'required|string|max:255',
            'id_input_area' => 'nullable|exists:input_areas,id',
            'field_name' => 'required|array|min:1',
            'field_name.*' => 'required|string|max:255',
        ]);

        // Create master form
        $masterForm = InputMasterForm::create([
            'id_user' => Auth::id(),
            'nama_form' => trim($request->nama_form),
            'id_input_area' => $request->id_input_area,
        ]);

        // Create fields
        $fieldNames = array_filter($request->field_name, function($value) {
            return !empty(trim($value));
        });

        foreach ($fieldNames as $index => $fieldName) {
            InputMasterFormField::create([
                'id_master_form' => $masterForm->id,
                'field_name' => trim($fieldName),
                'field_order' => $index + 1,
            ]);
        }

        return redirect()->route('input-master-forms.index')->with('success', 'Master Form berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InputMasterForm $inputMasterForm)
    {
        $this->checkPlantAccess($inputMasterForm);
        $inputMasterForm->load('fields');
        
        $user = Auth::user();
        
        // Get areas based on user plant
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $areas = InputArea::orderBy('nama_area')->get();
        } else {
            $areas = InputArea::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->orderBy('nama_area')->get();
        }
        
        return view('super-admin.input-master-form.edit', compact('inputMasterForm', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InputMasterForm $inputMasterForm)
    {
        $this->checkPlantAccess($inputMasterForm);
        
        $request->validate([
            'nama_form' => 'required|string|max:255',
            'id_input_area' => 'nullable|exists:input_areas,id',
            'field_name' => 'required|array|min:1',
            'field_name.*' => 'required|string|max:255',
        ]);

        // Update master form
        $inputMasterForm->update([
            'nama_form' => trim($request->nama_form),
            'id_input_area' => $request->id_input_area,
        ]);

        // Delete old fields
        $inputMasterForm->fields()->delete();

        // Create new fields
        $fieldNames = array_filter($request->field_name, function($value) {
            return !empty(trim($value));
        });

        foreach ($fieldNames as $index => $fieldName) {
            InputMasterFormField::create([
                'id_master_form' => $inputMasterForm->id,
                'field_name' => trim($fieldName),
                'field_order' => $index + 1,
            ]);
        }

        return redirect()->route('input-master-forms.index')->with('success', 'Master Form berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputMasterForm $inputMasterForm)
    {
        $this->checkPlantAccess($inputMasterForm);
        
        $inputMasterForm->delete();
        return redirect()->route('input-master-forms.index')->with('success', 'Master Form berhasil dihapus!');
    }

    /**
     * Check if user has access to master form based on plant
     */
    private function checkPlantAccess(InputMasterForm $inputMasterForm)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($inputMasterForm->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}