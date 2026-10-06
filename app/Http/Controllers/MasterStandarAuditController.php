<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterStandarAudit;
use App\Models\InputArea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MasterStandarAuditController extends Controller
{
    /**
     * Tampilkan Halaman Index Master Standar Audit (Form Batch & Table Aturan)
     */
    public function index(Request $request)
    {
        $selectedModule = $request->get('module');
        $perPage = $request->get('per_page', 10);
        
        // Ambil list modules dari config
        $modules = $this->getQcModules();

        // Query aturan audit dengan filter
        $standars = MasterStandarAudit::with('area')
            ->when($selectedModule, function ($q) use ($selectedModule) {
                $q->where('module_name', $selectedModule);
            })
            ->orderBy('module_name', 'asc')
            ->orderBy('column_name', 'asc')
            ->paginate($perPage)
            ->appends($request->all());

        // Ambil list areas untuk dropdown
        $user = Auth::user();
        $areas = InputArea::when($user->role && strtolower($user->role->role) !== 'superadmin', function ($q) use ($user) {
                $q->whereHas('user', function ($qu) use ($user) {
                    $qu->where('id_plant', $user->getEffectivePlantId());
                });
            })
            ->orderBy('nama_area', 'asc')
            ->get();

        return view('master-standar-audit.index', compact('modules', 'standars', 'areas', 'selectedModule', 'perPage'));
    }

    /**
     * Endpoint AJAX untuk Mengambil Kolom Auditable Berdasarkan Modul yang Dipilih
     */
    public function getColumns(Request $request)
    {
        $moduleKey = $request->get('module');
        $modulesConfig = config('master_audit.modules', []);

        if (!isset($modulesConfig[$moduleKey])) {
            return response()->json([]);
        }

        $moduleConfig = $modulesConfig[$moduleKey];
        $columns = [];

        if (isset($moduleConfig['fields'])) {
            foreach ($moduleConfig['fields'] as $colName => $fieldConfig) {
                $columns[] = [
                    'name'       => $colName,
                    'label'      => $fieldConfig['label'] ?? $colName,
                    'type'       => $fieldConfig['type'] ?? 'status_checklist',
                    'table_name' => $moduleConfig['main_table'] ?? $moduleKey,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'columns' => $columns
        ]);
    }

    /**
     * Simpan / Update Aturan (Multi Batch Item Form Input)
     */
    public function store(Request $request)
    {
        // Validasi input batch
        $request->validate([
            'module_name' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.column_name' => 'required|string',
            'items.*.parameter_type' => 'required|in:suhu,suhu_ruang,suhu_kondisi,suhu_pusat,suhu_pemasakan,status_checklist',
        ]);

        $moduleName = $request->module_name;
        $savedCount = 0;
        $skippedCount = 0;

        try {
            DB::transaction(function () use ($request, $moduleName, &$savedCount, &$skippedCount) {
                foreach ($request->items as $item) {
                    // Skip jika checkbox tidak dicentang
                    if (empty($item['enabled'])) {
                        $skippedCount++;
                        continue;
                    }

                    $tableName = !empty($item['table_name']) ? $item['table_name'] : $moduleName;

                    MasterStandarAudit::updateOrCreate(
                        [
                            'module_name' => $moduleName,
                            'area_id' => null, // Batch create untuk aturan global
                            'column_name' => $item['column_name'],
                        ],
                        [
                            'table_name' => $tableName,
                            'parameter_type' => $item['parameter_type'],
                            'min_value' => isset($item['min_value']) && $item['min_value'] !== '' ? $item['min_value'] : null,
                            'max_value' => isset($item['max_value']) && $item['max_value'] !== '' ? $item['max_value'] : null,
                            'standar_value' => isset($item['standar_value']) && $item['standar_value'] !== '' ? $item['standar_value'] : null,
                            'auto_fix_status' => !empty($item['auto_fix_status']),
                        ]
                    );
                    
                    $savedCount++;
                }
            });

            $message = "Berhasil menyimpan {$savedCount} aturan audit!";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} item dilewati karena tidak dicentang)";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan aturan: ' . $e->getMessage());
        }
    }

    /**
     * Update Aturan via Modal Edit
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'parameter_type' => 'required|in:suhu,suhu_ruang,suhu_kondisi,suhu_pusat,suhu_pemasakan,status_checklist',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'standar_value' => 'nullable|string',
        ]);

        try {
            $rule = MasterStandarAudit::where('uuid', $uuid)->firstOrFail();
            
            $rule->update([
                'area_id' => $request->area_id ?: null,
                'parameter_type' => $request->parameter_type,
                'min_value' => $request->min_value,
                'max_value' => $request->max_value,
                'standar_value' => $request->standar_value,
                'auto_fix_status' => $request->has('auto_fix_status') ? true : false,
            ]);

            return redirect()->back()->with('success', 'Aturan master audit berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui aturan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Aturan Standar Audit
     */
    public function destroy($uuid)
    {
        try {
            $rule = MasterStandarAudit::where('uuid', $uuid)->firstOrFail();
            $rule->delete();

            return redirect()->back()->with('success', 'Aturan standar audit berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus aturan: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk mengambil list modul QC dari config
     */
    protected function getQcModules(): array
    {
        $modules = config('master_audit.modules', []);
        $result = [];

        foreach ($modules as $key => $config) {
            $result[$key] = $config['label'] ?? ucfirst(str_replace('_', ' ', $key));
        }

        return $result;
    }
}
