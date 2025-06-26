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
        $dataQuery = JenisKonten::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(JenisKontenRequest $request, $id)
    {
        $dataQueryResponse = $this->show($id);
        if ($dataQueryResponse->getStatusCode() === 404) {
            return $dataQueryResponse;
        }
        $dataQuery = $dataQueryResponse->getOriginalContent(); // Ambil instance model dari respons
        $dataQuery->update($request->all());
        return response()->json($dataQuery, 200);
    }

    public function destroy($id)
    {
        $dataQueryResponse = $this->show($id);
        if ($dataQueryResponse->getStatusCode() === 404) {
            return $dataQueryResponse;
        }
        $dataQuery = $dataQueryResponse->getOriginalContent(); // Ambil instance model dari respons
        $dataQuery->delete();
        return response()->json(null, 204);
    }
}
