<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\KontenRequest;
use App\Http\Resources\KontenResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;

class KontenController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Konten::with(['jeniskonten', 'user', 'publikasi.user'])
            ->withCount([
                'komentar' => function ($query) {
                    $query->where('is_publikasi', 1);
                },
                'likedislike'
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $dataQuery->whereHas('jeniskonten', function ($q) use ($jenis) {
                //slug pada jenis konten
                $q->where('slug', $jenis);
            });
        }

        if ($request->filled('publikasi')) {
            $publikasiValue = $request->publikasi;
            if ($publikasiValue == 1) {
                $dataQuery->whereHas('publikasi', function ($q) {
                    $q->where('is_publikasi', 1);
                });
            } elseif ($publikasiValue == 0) {
                $dataQuery->whereHas('publikasi', function ($q) {
                    $q->where('is_publikasi', 0);
                });
            } elseif ($publikasiValue == 2) {
                $dataQuery->whereDoesntHave('publikasi');
            }
        }

        if ($request->filled('slug')) {
            $dataQuery->where('slug', $request->slug);
        }

        if ($request->filled('urut')) {
            if ($request->urut == "populer")
                $dataQuery->orderBy('jumlah_akses', 'desc');
        }

        $dataQuery->orderBy('waktu', 'desc')->orderBy('judul', 'asc');


        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);
        return KontenResource::collection($data);
    }

    public function store(KontenRequest $request)
    {
        DB::beginTransaction();
        try {
            $uploadFile = uploadFile($request, 'file', 'kontens/' . date('Y') . '/' . date('m'));
            if ($uploadFile !== false) {
                $request->merge(['thumbnail' => $uploadFile['path']]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }
            $dataSave = Konten::create($request->all());

            // auto publish ketika admin
            if (is_admin() || is_editor()) {
                $data = [
                    'is_publikasi' => 1,
                    'konten_id' => $dataSave->id,
                    'user_id' => auth()->id(),
                ];
                Publikasi::create($request->all());
            }
            // $dataQuery = Konten::where('id', $dataSave->id)->first();
            // $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
            DB::commit();
            return response()->json($dataSave, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = Konten::with(['jeniskonten', 'user', 'komentar', 'likedislike', 'publikasi.user'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        // return response()->json($dataQuery);
        return new KontenResource($dataQuery);
    }

    public function update(KontenRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = Konten::findOrFail($id);
            if ($request->has('file')) {
                $path = $dataQuery->thumbnail;
                $uploadFile = uploadFile($request, 'file', 'kontens/' . date('Y') . '/' . date('m'));
                if ($uploadFile !== false) {
                    $request->merge(['thumbnail' => $uploadFile['path']]);

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

    public function updateJumlahAkses($id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = Konten::findOrFail($id);

            $dataUpdate = [
                'jumlah_akses' => $dataQuery->jumlah_akses + 1,
            ];

            $dataQuery->update($dataUpdate);
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
            $publikasi = Publikasi::where('konten_id', $id)->first();
            if ($publikasi)
                $publikasi->delete();


            $dataQuery = Konten::findOrFail($id);
            $path = $dataQuery->thumbnail;
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
