<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\KomentarRequest;

class KomentarController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Komentar::with(['konten', 'file', 'user',])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('komentar', 'like', '%' . $search . '%');
            });
        }


        if ($request->filled('konten_id')) {
            $dataQuery->where('konten_id', $request->konten_id);
        } else if ($request->filled('file_id')) {
            $dataQuery->where('file_id', $request->file_id);
        }

        if ($request->filled('publikasi')) {
            $publikasiValue = $request->publikasi;
            if ($publikasiValue == 1) {
                $dataQuery->where('is_publikasi', 1);
            } elseif ($publikasiValue == 0) {
                $dataQuery->where('is_publikasi', 0);
            } elseif ($publikasiValue == 2) {
                $dataQuery->whereNull('is_publikasi');
            }
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(KomentarRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = Komentar::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = Komentar::with(['konten', 'user', 'file'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(KomentarRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = Komentar::findOrFail($id);
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
            $dataQuery = Komentar::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
