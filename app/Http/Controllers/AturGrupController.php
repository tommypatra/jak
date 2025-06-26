<?php

namespace App\Http\Controllers;

use App\Models\AturGrup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AturGrupRequest;

class AturGrupController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = AturGrup::with(['grup', 'user'])->orderBy('grup_id', 'asc')->orderBy('user_id', 'asc');

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(AturGrupRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->grup_id as $grup_id) {
                AturGrup::create([
                    'user_id' => $request->user_id,
                    'grup_id' => $grup_id,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = AturGrup::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(AturGrupRequest $request, $id)
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
