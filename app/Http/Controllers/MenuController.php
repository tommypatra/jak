<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;

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

        if ($request->filled('showall')) {
            $dataQuery = $dataQuery->get();
            $startingNumber = 1;
        } else {
            $paging = 25;
            if ($request->filled('paging')) {
                $paging = $request->paging;
            }
            $dataQuery = $dataQuery->paginate($paging);
            $startingNumber = ($dataQuery->currentPage() - 1) * $dataQuery->perPage() + 1;
        }

        $dataQuery->transform(function ($item) use (&$startingNumber) {
            $item->setAttribute('nomor', $startingNumber++);
            $item->setAttribute('updated_at_format', dbDateTimeFormat($item->updated_at));
            return $item;
        });

        return response()->json($dataQuery);
    }

    public function store(MenuRequest $request)
    {
        $dataSave = Menu::create($request->all());
        $dataQuery = Menu::where('id', $dataSave->id)
            ->first();
        $dataQuery->updated_at_format = dbDateTimeFormat($dataQuery->updated_at);
        return response()->json($dataQuery, 201);
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
        $dataQueryResponse = $this->show($id);
        if ($dataQueryResponse->getStatusCode() === 404) {
            return $dataQueryResponse;
        }
        $dataQuery = $dataQueryResponse->getOriginalContent(); // Ambil instance model dari respons
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
