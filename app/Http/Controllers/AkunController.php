<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AturGrup;
use Illuminate\Http\Request;
use App\Http\Requests\AkunRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AkunController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = User::select('users.*')
            ->leftJoin('profils', 'users.id', '=', 'profils.user_id')
            ->leftJoin('jabatans', 'profils.jabatan_id', '=', 'jabatans.id')
            ->leftJoin('unit_kerjas', 'profils.unit_kerja_id', '=', 'unit_kerjas.id')
            ->with([
                'aturGrup.grup',
                'profil.jabatan',
                'profil.unitKerja'
            ])
            ->orderBy('jabatans.urut', 'asc')
            ->orderBy('unit_kerjas.urut', 'asc')
            ->orderBy('users.name', 'asc');

        if ($request->filled('user_id')) {
            $dataQuery->where('users.id', $request->user_id);
        }

        if ($request->filled('kategori')) {
            if ($request->kategori == 'pimpinan') {
                $dataQuery->whereNotNull('profils.jabatan_id');
            } elseif ($request->kategori == 'dosen') {
                $dataQuery->where('profils.is_dosen', 1);
            } elseif ($request->kategori == 'administrasi') {
                $dataQuery->where('profils.is_administrasi', 1);
            }
        }


        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('users.name', 'like', '%' . $request->search . '%')
                    ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }

        $limit = $request->input('limit', 25);

        return response()->json(
            $dataQuery->paginate($limit)
        );
    }


    public function store(AkunRequest $request)
    {

        try {
            DB::beginTransaction();
            $dataSave = $request->validated();
            $dataSave['password'] = Hash::make($dataSave['password']);
            $data = User::create($dataSave);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = User::with(['aturGrup.grup', 'profil', 'profil.jabatan', 'profil.unitKerja'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(AkunRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = User::findOrFail($id);
            $dataSave = $request->validated();
            if ($request->password) {
                $dataSave['password'] = Hash::make($dataSave['password']);
            }
            $dataQuery->update($dataSave);
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
            $dataQuery = User::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
