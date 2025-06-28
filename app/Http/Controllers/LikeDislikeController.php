<?php

namespace App\Http\Controllers;

use App\Models\LikeDislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeDislikeController extends Controller
{
    public function index(Request $request)
    {
        $count = 0;
        if ($request->filled('konten_id')) {
            $count = LikeDislike::where('konten_id', $request->konten_id)->count();
        } elseif ($request->filled('file_id')) {
            $count = LikeDislike::where('file_id', $request->file_id)->count();
        }
        return response()->json(['count' => $count]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = LikeDislike::create($request->all());
            DB::commit();

            // Hitung total like berdasarkan field yang dikirim
            if ($request->filled('file_id')) {
                $jumlah_like = LikeDislike::where('file_id', $request->file_id)->count();
            } elseif ($request->filled('konten_id')) {
                $jumlah_like = LikeDislike::where('konten_id', $request->konten_id)->count();
            } else {
                $jumlah_like = 0;
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data,
                'jumlah_like' => $jumlah_like
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            DB::beginTransaction();
            $data = LikeDislike::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = LikeDislike::findOrFail($id);
            $dataQuery->update($request->all());
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
            $dataQuery = LikeDislike::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
