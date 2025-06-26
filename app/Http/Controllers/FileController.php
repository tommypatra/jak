<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = File::with(['user', 'jeniskonten', 'publikasi.user'])
            ->withCount([
                'komentar' => function ($query) {
                    $query->where('is_publikasi', 1);
                },
                'likedislike'
            ]);

        // if (!$request->filled('is_web')) {
        //     if (!is_admin() && !is_editor())
        //         $dataQuery->where('user_id', auth()->id());
        // }

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

        return FileResource::collection($data);
    }

    public function store(FileRequest $request)
    {
        DB::beginTransaction();

        try {
            $uploadFile = uploadFile($request, 'file', 'files/' . date('Y') . '/' . date('m'));
            if ($uploadFile !== false) {
                $request->merge([
                    'path' => $uploadFile['path'],
                    'ukuran' => $uploadFile['ukuran'],
                    'jenis_file' => $uploadFile['jenis']
                ]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }

            if ($request->hasFile('coverfile')) {
                $uploadFile = uploadFile($request, 'coverfile', 'covers/' . date('Y'));
                if ($uploadFile !== false) {
                    $request->merge([
                        'cover' => $uploadFile['path'],
                    ]);
                } else {
                    return response()->json(['message' => 'Gagal mengunggah cover'], 500);
                }
            }

            // Simpan file
            $dataSave = File::create($request->all());

            // auto publish ketika admin
            if (is_admin() || is_editor()) {
                $data = [
                    'is_publikasi' => 1,
                    'file_id' => $dataSave->id,
                    'user_id' => auth()->id(),
                ];
                Publikasi::create($data);
            }
            // Ambil data yang baru saja disimpan
            // $dataQuery = File::where('id', $dataSave->id)->first();
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
        $dataQuery = File::with(['user', 'komentar', 'likedislike', 'jeniskonten', 'publikasi.user'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        // return response()->json($dataQuery);
        return new FileResource($dataQuery);
    }

    public function update(FileRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dataQuery = File::findOrFail($id);
            if ($request->hasFile('file')) {
                $path = $dataQuery->path;
                $uploadFile = uploadFile($request, 'file', 'files/' . date('Y') . '/' . date('m'));
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


            if ($request->hasFile('coverfile')) {
                // echo "ada dan upload ya";
                $cover = $dataQuery->cover;
                $uploadFile = uploadFile($request, 'coverfile', 'covers/' . date('Y'));
                if ($uploadFile !== false) {
                    // dd($uploadFile['path']);
                    $request->merge([
                        'cover' => $uploadFile['path'],
                    ]);
                    if (Storage::disk('public')->exists($cover)) {
                        Storage::disk('public')->delete($cover);
                    }
                } else {
                    return response()->json(['message' => 'Gagal mengunggah cover'], 500);
                }
            }

            // dd($request->all());
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
            $dataQuery = File::findOrFail($id);
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
            $publikasi = Publikasi::where('file_id', $id)->first();
            if ($publikasi)
                $publikasi->delete();

            $dataQuery = File::findOrFail($id);
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
