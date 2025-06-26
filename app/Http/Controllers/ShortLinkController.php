<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Http\Requests\ShortLinkRequest;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = ShortLink::with('user')->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(ShortLinkRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = ShortLink::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = ShortLink::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(ShortLinkRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = ShortLink::findOrFail($id);
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
            $dataQuery = ShortLink::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function redirect($slug)
    {
        $shortLink = ShortLink::where('slug', $slug)->first();
        if (!$shortLink) {
            return response()->json(['message' => 'slug tidak valid'], 404);
        }
        $shortLink->increment('jumlah_akses');
        return redirect()->away($shortLink->url_src);
    }
}
