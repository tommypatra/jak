<?php

namespace App\Http\Controllers;

use App\Models\SlideShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SlideShowRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\SlideShowResource;
use Illuminate\Support\Facades\File as FileFacade;


class SlideShowController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = SlideShow::with(['user'])->orderBy('updated_at', 'desc')->orderBy('judul', 'asc');
        if (!$request->filled('web')) {
            if (!is_admin(auth()->id()))
                $dataQuery->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $dataQuery->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%'); //slug pada SlideShow
        }

        if ($request->filled('publikasi')) {
            $publikasiValue = $request->publikasi;
            if ($publikasiValue == 1) {
                $dataQuery->where('is_publikasi', 1);
            } elseif ($publikasiValue == 0) {
                $dataQuery->where('is_publikasi', 0);
            } elseif ($publikasiValue == 2) {
                $dataQuery->whereNull('is_publikasi');
            }
        }

        if (!$request->filled('web')) {
            if (!is_admin() && !is_editor())
                $dataQuery->where('user_id', auth()->id());
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return SlideShowResource::collection($data);
    }

    public function store(SlideShowRequest $request)
    {
        DB::beginTransaction();

        try {
            $uploadFile = uploadFile($request, 'file', 'slideshows/' . date('Y'));
            if ($uploadFile !== false) {
                $request->merge([
                    'path' => $uploadFile['path'],
                ]);
            } else {
                return response()->json(['message' => 'Gagal mengunggah file'], 500);
            }
            $dataSave = SlideShow::create($request->all());
            DB::commit();
            return response()->json($dataSave, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = SlideShow::with(['user'])->find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        // return response()->json($dataQuery);
        return new SlideShowResource($dataQuery);
    }

    public function update(SlideShowRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $dataQuery = SlideShow::findOrFail($id);
            if ($request->has('file')) {
                $path = $dataQuery->path;
                $uploadFile = uploadFile($request, 'file', 'slideshows/' . date('Y'));
                if ($uploadFile !== false) {
                    $request->merge([
                        'path' => $uploadFile['path'],
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
            $dataQuery = SlideShow::findOrFail($id);
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
