<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MenuInformasi;
use App\Http\Requests\StoreMenuRequest;

class MenuInformasiController extends Controller
{
    public function index()
    {
        $tree = MenuInformasi::whereNull('parent_id')
            ->orderBy('urutan')
            ->with('children')
            ->get();
        return Inertia::render('Admin/Ppid/MenuInformasi', [
            'tree' => $tree,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Ppid/FormTambahMenuInformasi', []);
    }

    public function createSubMenu($id)
    {
        $menu = MenuInformasi::query()->select('id', 'nama_menu')->where('id', $id)->first();
        if ($menu === null) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }
        return Inertia::render('Admin/Ppid/FormTambahMenuInformasi', [
            'mode' => 'createSubMenu',
            'parentOptions' => $menu,
            'selectedParentId' => $id,
        ]);
    }

    public function getMenuInformasi(Request $request)
    {
        $search = $request->input('q');
        $editingId  = $request->input('menu_id');
        $excludedIds = [];
        if ($editingId) {
            $menu = MenuInformasi::find($editingId);
            if ($menu) {
                $excludedIds = [$menu->id, ...$this->getDescendantIds($menu)];
            }
        }

        $menuInformasi = MenuInformasi::query()
            ->when($excludedIds !== [], fn($q) => $q->whereNotIn('id', $excludedIds))
            ->where('nama_menu', 'like', '%' . $search . '%')
            ->select('id', 'nama_menu')
            ->get();
        return response()->json($menuInformasi);
    }


    public function edit(MenuInformasi $menuInformasi)
    {
        $menuInformasi->load('parent');
        return Inertia::render('Admin/Ppid/FormTambahMenuInformasi', [
            'mode' => 'edit',
            'selectedParentId' => $menuInformasi->parent_id,
            'menu' => $menuInformasi,
        ]);
    }

    public function store(StoreMenuRequest $request)
    {
        $validatedData = $request->validated();
        if ($validatedData['parent_id'] !== null) {
            $max_urutan = MenuInformasi::where('parent_id', $validatedData['parent_id'])->max('urutan') + 1;
            if ($validatedData['urutan'] >= $max_urutan) {
                $validatedData['urutan'] = $max_urutan;
            } else {
                $cekUrutan = MenuInformasi::where('parent_id', $validatedData['parent_id'])->where('urutan', '>=', $validatedData['urutan'])->count();
                if ($cekUrutan > 0) {
                    $validatedData['urutan'] =  $max_urutan;
                }
            }
        }
        MenuInformasi::create($validatedData);

        return redirect()
            ->route('admin.ppid.menu-informasi')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(StoreMenuRequest $request, MenuInformasi $menuInformasi)
    {
        $validatedData = $request->validated();
        $excludedIds = [$menuInformasi->id, ...$this->getDescendantIds($menuInformasi)];

        if (in_array($validatedData['parent_id'], $excludedIds)) {
            return redirect()->back()->withErrors([
                'parent_id' => 'Menu induk tidak boleh menu ini sendiri atau salah satu sub-menunya.',
            ]);
        }
        $menuInformasi->update($validatedData);
        return redirect()
            ->route('admin.ppid.menu-informasi')
            ->with('success', 'Menu berhasil diupdate.');
    }

    public function destroy(MenuInformasi $menuInformasi)
    {
        $nama = $menuInformasi->nama_menu;
        $menuInformasi->delete();

        return redirect()
            ->route('admin.ppid.menu-informasi')
            ->with('success', "Menu \"{$nama}\" beserta isinya berhasil dihapus.");
    }


    /**
     * Ambil semua id keturunan (anak, cucu, dst) dari satu menu,
     * dipakai supaya form edit tidak bisa membuat referensi melingkar.
     */
    private function getDescendantIds(MenuInformasi $menu): array
    {
        $menu->loadMissing('childrenRecursive');

        $ids = [];
        $walk = function ($nodes) use (&$walk, &$ids) {
            foreach ($nodes as $node) {
                $ids[] = $node->id;
                $walk($node->children);
            }
        };
        $walk($menu->children);

        return $ids;
    }
}
