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
        $request['password'] = Hash::make($request['password']);

        $dataSave = User::create($request->all());
        $dataQuery = User::where('id', $dataSave->id)
            ->first();
        $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
        return response()->json($dataQuery, 201);
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
        $dataQueryResponse = $this->show($id);
        if ($dataQueryResponse->getStatusCode() === 404) {
            return $dataQueryResponse;
        }
        $dataQuery = $dataQueryResponse->getOriginalContent(); // Ambil instance model dari respons
        // dd($request['password']);
        if ($request['password'])
            $request['password'] = Hash::make($request['password']);
        else
            unset($request['password']);
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
