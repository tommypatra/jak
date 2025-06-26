<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengaturanWeb;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PengaturanWebRequest;
use App\Http\Resources\PengaturanWebResource;

class PengaturanWebController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = PengaturanWeb::with('user')->orderBy('id', 'asc');

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return PengaturanWebResource::collection($data);
    }

    public function store(PengaturanWebRequest $request)
    {
        DB::beginTransaction();
        try {

            $uploadFile = uploadFile($request, 'fileicon', 'images');
            if ($uploadFile !== false) {
                $request->merge([
                    'icon' => $uploadFile['path'],
                ]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }

            $uploadFile = uploadFile($request, 'filelogo', 'images');
            if ($uploadFile !== false) {
                $request->merge([
                    'logo' => $uploadFile['path'],
                ]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }

            $dataSave = PengaturanWeb::create($request->all());
            DB::commit();
            return response()->json($dataSave, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = PengaturanWeb::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        // return response()->json($dataQuery);
        return new PengaturanWebResource($dataQuery);
    }


    public function update(PengaturanWebRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = PengaturanWeb::findOrFail($id);

            if ($request->hasFile('fileicon')) {
                $path = $dataQuery->icon;
                $uploadFile = uploadFile($request, 'fileicon', 'images');
                if ($uploadFile !== false) {
                    $request->merge([
                        'icon' => $uploadFile['path'],
                    ]);

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                } else {
                    return response()->json(['message' => 'Gagal mengunggah file'], 500);
                }
            }
            if ($request->hasFile('filelogo')) {
                $path = $dataQuery->logo;
                $uploadFile = uploadFile($request, 'filelogo', 'images');
                if ($uploadFile !== false) {
                    $request->merge([
                        'logo' => $uploadFile['path'],
                    ]);

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                } else {
                    return response()->json(['message' => 'Gagal mengunggah file'], 500);
                }
            }
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
            $dataQuery = PengaturanWeb::findOrFail($id);
            $icon = $dataQuery->icon;
            $logo = $dataQuery->logo;
            $dataQuery->delete();
            if (Storage::disk('public')->exists($icon)) {
                Storage::disk('public')->delete($icon);
            }
            if (Storage::disk('public')->exists($logo)) {
                Storage::disk('public')->delete($logo);
            }
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
