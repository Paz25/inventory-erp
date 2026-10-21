<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Contracts\Encryption\DecryptException;
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
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('product_name', 'like', "%{$search}%")
                        ->orWhere('product_category', 'like', "%{$search}%");
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
            Item::create($request->itemData());
        } catch (UniqueConstraintViolationException $e) {
            report($e);
            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        }

        return redirect()->route('items')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(string $encryptedId)
    {
        try {
            $item = Item::findOrFail(Crypt::decrypt($encryptedId));

            return response()->json([
                'kode' => $item->code,
                'nama' => $item->product_name,
                'kategori' => $item->product_category,
                'jumlah' => $item->quantity,
                'update_url' => route('items.update', $encryptedId),
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'message' => 'Barang tidak ditemukan.',
            ], 404);
        }
    }

    public function update(ItemRequest $request, string $encryptedId)
    {
        try {
            $item = Item::findOrFail(Crypt::decrypt($encryptedId));
            $item->update($request->itemData());

            return redirect()->route('items')->with('success', 'Barang berhasil diperbarui.');
        } catch (DecryptException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal diperbarui.');
        } catch (UniqueConstraintViolationException $e) {
            report($e);

            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        }
    }

    public function destroy(string $encryptedId)
    {
        try {
            $item = Item::findOrFail(Crypt::decrypt($encryptedId));

            $item->delete();
            return redirect()->route('items')->with('success', 'Barang berhasil dihapus.');
        } catch (DecryptException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal dihapus.');
        } catch (QueryException $e) {
            report($e);

            return back()->with('error', 'Barang gagal dihapus.');
        }
    }
}
