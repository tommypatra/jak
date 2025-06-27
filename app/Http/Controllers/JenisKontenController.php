<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\JenisKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\JenisKontenRequest;

class JenisKontenController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = JenisKonten::with('user')->orderBy('kategori', 'asc')->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('kategori', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $dataQuery->where('kategori',  $request->kategori);
        }

        if ($request->filled('slug')) {
            $dataQuery->where('slug', 'like', '%' . $request->slug . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function jumlahJenisPublikasi($kategori = "artikel")
    {
        $dataQuery = DB::table('jenis_kontens as jk')

            ->select(
                'jk.id',
                'jk.nama',
                'jk.slug',
                DB::raw('COUNT(p.id) as jumlah_terbit')
            )
            ->where(DB::raw('LOWER(jk.kategori)'), strtolower($kategori))
            ->groupBy('jk.id', 'jk.nama', 'jk.slug')
            ->orderBy('jk.nama', 'asc');

        if (strtolower($kategori) == "artikel") {
            $dataQuery->leftJoin('kontens as k', 'jk.id', '=', 'k.jenis_konten_id')
                ->leftJoin('publikasis as p', function ($join) {
                    $join->on('k.id', '=', 'p.konten_id')
                        ->where('p.is_publikasi', '1');
                });
        } elseif (strtolower($kategori) == "file") {
            $dataQuery->leftJoin('files as k', 'jk.id', '=', 'k.jenis_konten_id')
                ->leftJoin('publikasis as p', function ($join) {
                    $join->on('k.id', '=', 'p.file_id')
                        ->where('p.is_publikasi', '1');
                });
        }

        $data = $dataQuery->get();
        return response()->json($data);
    }


    public function store(JenisKontenRequest $request)
    {
        $dataSave = JenisKonten::create($request->all());
        $dataQuery = JenisKonten::with('user')
            ->where('id', $dataSave->id)
            ->first();
        $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
        return response()->json($dataQuery, 201);
    }

    public function show($id)
    {
        try {
            DB::beginTransaction();
            $data = JenisKonten::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function update(JenisKontenRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = JenisKonten::findOrFail($id);
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
            $dataQuery = JenisKonten::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
