<?php

namespace App\Http\Controllers;

use App\Models\LikeDislike;
use Illuminate\Http\Request;

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
        $dataQuery = LikeDislike::create($request->all());
        $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
        return response()->json($dataQuery, 201);
    }

    public function show($id)
    {
        $dataQuery = LikeDislike::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(Request $request, $id)
    {
        $dataQueryResponse = $this->show($id);
        if ($dataQueryResponse->getStatusCode() === 404) {
            return $dataQueryResponse;
        }
        $dataQuery = $dataQueryResponse->getOriginalContent(); // Ambil instance model dari respons

        $dataQuery->update($request->all());
        return $dataQuery;
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
