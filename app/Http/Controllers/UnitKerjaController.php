<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Requests\UnitKerjaRequest;
use Illuminate\Support\Facades\DB;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = UnitKerja::with(['unitKerjaParent'])->orderBy('urut', 'asc')->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(UnitKerjaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = UnitKerja::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = UnitKerja::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(UnitKerjaRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = UnitKerja::findOrFail($id);
            $dataQuery->update($request->validated());
            DB::commit();
            return response()->json($dataQuery, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = UnitKerja::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
