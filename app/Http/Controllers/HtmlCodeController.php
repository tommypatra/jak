<?php

namespace App\Http\Controllers;

use App\Models\HtmlCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HtmlCodeRequest;

class HtmlCodeController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = HtmlCode::with(['user']);

        if (!$request->filled('web')) {
            if (!is_admin() && !is_editor())
                $dataQuery->where('user_id', auth()->id());
        }


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
        try {
            DB::beginTransaction();
            $data = HtmlCode::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = HtmlCode::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(HtmlCodeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = HtmlCode::findOrFail($id);
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
            $dataQuery = HtmlCode::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
