<?php

namespace App\Http\Controllers;

use App\Models\HtmlCode;
use Illuminate\Http\Request;
use App\Http\Requests\HtmlCodeRequest;

class HtmlCodeController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = HtmlCode::with(['user']);

        if ($request->filled('search')) {
            $dataQuery->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('slug')) {
            $dataQuery->where('slug', $request->slug);
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(HtmlCodeRequest $request)
    {
        $dataQuery = HtmlCode::create($request->all());
        $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
        return response()->json($dataQuery, 201);
    }

    public function show($id)
    {
        $dataQuery = HtmlCode::with(['user'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(HtmlCodeRequest $request, $id)
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
