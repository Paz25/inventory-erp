<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $items = Item::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('dashboard.index', compact('items', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:items,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);
        Item::create($validated);
        return redirect()->route('dashboard')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'kode' => 'required|string|max:50|unique:items,kode',
    //         'nama' => 'required|string|max:255',
    //         'kategori' => 'required|string|max:100',
    //         'jumlah' => 'required|integer|min:0',
    //     ], [
    //         'kode.required' => 'Kode barang wajib diisi.',
    //         'kode.unique' => 'Kode barang sudah digunakan.',
    //         'kode.max' => 'Kode barang maksimal 50 karakter.',
    //         'nama.required' => 'Nama barang wajib diisi.',
    //         'kategori.required' => 'Kategori barang wajib diisi.',
    //         'jumlah.required' => 'Jumlah stok wajib diisi.',
    //         'jumlah.integer' => 'Jumlah stok harus berupa angka bulat.',
    //         'jumlah.min' => 'Jumlah stok tidak boleh negatif.',
    //     ]);

    //     Item::create($validated);

    //     return redirect()->route('dashboard')
    //         ->with('success', 'Barang berhasil ditambahkan.');
    // }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('items', 'kode')->ignore($item->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);
        $item->update($validated);
        return redirect()->route('dashboard')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('dashboard')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
