<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GaleriRequest;
use App\Http\Resources\GaleriResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Galeri::with(['user', 'publikasi.user'])
            ->orderBy('waktu', 'desc')->orderBy('judul', 'asc');

        if (!$request->filled('web')) {
            if (!is_admin() && !is_editor())
                $dataQuery->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $dataQuery->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('publikasi')) {
            $publikasiValue = $request->publikasi;
            if ($publikasiValue == 1) {
                $dataQuery->whereHas('publikasi', function ($q) {
                    $q->where('publikasis.is_publikasi', 1);
                });
            } elseif ($publikasiValue == 0) {
                $dataQuery->whereHas('publikasi', function ($q) {
                    $q->where('publikasis.is_publikasi', 0);
                });
            } elseif ($publikasiValue == 2) {
                $dataQuery->whereDoesntHave('publikasi');
            }
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return GaleriResource::collection($data);
    }

    public function store(GaleriRequest $request)
    {
        DB::beginTransaction();

        try {
            $uploadFile = uploadFile($request, 'file', 'galeris/' . date('Y') . '/' . date('m'));
            if ($uploadFile !== false) {
                $request->merge([
                    'path' => $uploadFile['path'],
                    'ukuran' => $uploadFile['ukuran'],
                    'jenis_file' => $uploadFile['jenis']
                ]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }
            $dataSave = Galeri::create($request->all());

            // auto publish ketika admin
            if (is_admin() || is_editor()) {
                $data = [
                    'is_publikasi' => 1,
                    'galeri_id' => $dataSave->id,
                    'user_id' => auth()->id(),
                ];
                Publikasi::create($data);
            }
            DB::commit();
            return response()->json($dataSave, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = Galeri::with(['user', 'publikasi.user'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        // return response()->json($dataQuery);
        return new GaleriResource($dataQuery);
    }

    public function update(GaleriRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $dataQuery = Galeri::findOrFail($id);
            if ($request->hasFile('file')) {
                $path = $dataQuery->path;
                $uploadFile = uploadFile($request, 'file', 'galeris/' . date('Y') . '/' . date('m'));
                if ($uploadFile !== false) {
                    $request->merge([
                        'path' => $uploadFile['path'],
                        'ukuran' => $uploadFile['ukuran'],
                        'jenis_file' => $uploadFile['jenis']
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
            $publikasi = Publikasi::where('galeri_id', $id)->first();
            if ($publikasi)
                $publikasi->delete();

            $dataQuery = Galeri::findOrFail($id);
            $path = $dataQuery->path;
            $dataQuery->delete();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
