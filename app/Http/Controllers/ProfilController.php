<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfilRequest;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Profil::with(['user'])->orderBy('id', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(ProfilRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $uploadFile = uploadFile($request, 'foto', 'foto-profil');
            if ($uploadFile !== false) {
                $validated['foto'] = $uploadFile['path'];
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }

            $data = Profil::create($validated);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = Profil::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(ProfilRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = Profil::findOrFail($id);

            $validated = $request->validated();
            if ($request->hasFile('foto')) {
                $path = $dataQuery->path;
                $uploadFile = uploadFile($request, 'foto', 'foto-profil');
                if ($uploadFile !== false) {
                    $validated['foto'] = $uploadFile['path'];

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                } else {
                    return response()->json(['message' => 'Gagal mengunggah file'], 500);
                }
            }
            // dd($request->validated());
            if (!isset($validated['is_administrasi'])) {
                $validated['is_administrasi'] = 0;
            }
            if (!isset($validated['is_dosen'])) {
                $validated['is_dosen'] = 0;
            }

            $dataQuery->update($validated);
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
            $dataQuery = Profil::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
