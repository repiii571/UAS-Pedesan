<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu untuk admin.
     */
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('admin.index', compact('menus'));
    }

    /**
     * Menampilkan form tambah menu.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'kategori'   => 'required|string',
            'harga'      => 'required|numeric|min:0',
            'stok'       => 'required|numeric|min:0',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_url' => 'nullable|url',
        ]);

        $data = collect($validated)->except('gambar')->toArray();

        if ($request->hasFile('gambar')) {
            // Disimpan ke storage/app/public/menus, diakses lewat /storage/menus/... (via storage:link)
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit menu.
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.edit', compact('menu'));
    }

    /**
     * Memperbarui data menu di database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'kategori'   => 'required|string',
            'harga'      => 'required|numeric|min:0',
            'stok'       => 'required|numeric|min:0',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_url' => 'nullable|url',
        ]);

        $menu = Menu::findOrFail($id);
        $data = collect($validated)->except('gambar')->toArray();

        if ($request->hasFile('gambar')) {
            // Hapus file lama biar storage tidak numpuk sampah
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Informasi menu berhasil diperbarui!');
    }

    /**
     * Menghapus menu dari database (sekaligus file gambarnya di storage, kalau ada).
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
