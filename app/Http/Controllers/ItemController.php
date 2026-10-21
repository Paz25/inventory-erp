<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use App\Http\Requests\ItemRequest;
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

        return view('items.index', compact('items', 'search'));
    }

    public function store(ItemRequest $request)
    {
        try {
            Item::create($request->validated());
        } catch (UniqueConstraintViolationException $e) {
            report($e);

            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        }
        return redirect()->route('items')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(ItemRequest $request, Item $item)
    {
        try {
            $item->update($request->validated());
        } catch (UniqueConstraintViolationException $e) {
            report($e);

            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        }
        return redirect()->route('items')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        try {
            $item->delete();
        } catch (QueryException $e) {
            report($e);

            return back()->with('error', 'Barang gagal dihapus.');
        }
        return redirect()->route('items')->with('success', 'Barang berhasil dihapus.');
    }
}
