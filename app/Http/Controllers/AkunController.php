<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AkunRequest;
use Illuminate\Support\Facades\Hash;


class AkunController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = User::with(['aturgrup.grup'])->orderBy('name', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
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
        $dataQuery = User::with(['aturgrup.grup'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(AkunRequest $request, $id)
{
    DB::beginTransaction();
    try {
        $dataQuery = AturGrup::findOrFail($id);
        $dataSave = $request->validated();
        if ($request->password) {
            $dataSave['password'] = Hash::make($dataSave['password']);
        }
        $dataQuery->update($dataSave);
        DB::commit();
        return response()->json($dataQuery, 200);

    } catch (\Exception $e) {


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
