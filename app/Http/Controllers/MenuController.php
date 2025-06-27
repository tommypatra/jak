<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function loadMenu($parentId = null)
    {
        $menus = Menu::where('menu_id', $parentId)->orderBy('menu_id')->orderBy('urut')->get();
        $result = [];
        foreach ($menus as $menu) {
            $item = [
                'id' => $menu->id,
                'text' => $menu->nama,
                'endpoint' => $menu->endpoint,
            ];
            $item['url'] = ($menu->url) ? $menu->url : '#';
            $submenus = $this->loadMenu($menu->id);
            if (!empty($submenus)) {
                $item['inc'] = $submenus;
            }
            $result[] = $item;
        }
        return $result;
    }

    public function getMenu()
    {
        $menu = $this->loadmenu();
        array_unshift($menu, ['id' => 0, 'text' => 'Menu Root']);
        return response()->json($menu);
    }

    public function index(Request $request)
    {
        $dataQuery = Menu::with(['user'])->orderBy('menu_id')->orderBy('urut')->orderBy('nama');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('url', 'like', '%' . $request->search . '%');
        }

        $limit = $request->input('limit', 25);
        $data = $dataQuery->paginate($limit);

        return response()->json($data);
    }

    public function store(MenuRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = Menu::create($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = Menu::find($id);
        if (!$dataQuery) {
            return response()->json(['message' => 'data tidak ditemukan'], 404);
        }
        return response()->json($dataQuery);
    }

    public function update(MenuRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $dataQuery = Menu::findOrFail($id);
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
            $dataQuery = Menu::findOrFail($id);
            $dataQuery->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
