<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

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
            DB::transaction(function () use ($request) {
                $item = Item::create($request->itemData());

                $item->movements()->create([
                    'type' => 'initial',
                    'quantity' => $item->quantity,
                ]);
            });

            return redirect()->route('items')->with('success', 'Barang berhasil ditambahkan.');
        } catch (UniqueConstraintViolationException $e) {
            report($e);

            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', 'Barang gagal disimpan.');
        }
    }

    public function show(string $encryptedId): JsonResponse
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
        } catch (DecryptException | ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Barang tidak ditemukan.',
            ], 404);
        }
    }

    public function update(ItemRequest $request, string $encryptedId)
    {
        try {
            DB::transaction(function () use ($request, $encryptedId) {
                $item = Item::whereKey(Crypt::decrypt($encryptedId))->lockForUpdate()->firstOrFail();
                $before = $item->quantity;

                $item->update($request->itemData());

                if ($item->wasChanged('quantity')) {
                    $diff = $item->quantity - $before;
                    $item->movements()->create([
                        'type' => $diff > 0 ? 'masuk' : 'keluar',
                        'quantity' => abs($diff),
                    ]);
                }
            });

            return redirect()->route('items')->with('success', 'Barang berhasil diperbarui.');
        } catch (DecryptException | ModelNotFoundException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal diperbarui.');
        } catch (UniqueConstraintViolationException $e) {
            report($e);

            return back()->withInput()->with('error', 'Kode barang sudah digunakan.');
        } catch (QueryException $e) {
            report($e);

            return back()->with('error', 'Barang gagal diperbarui.');
        }
    }

    public function destroy(string $encryptedId)
    {
        try {
            $item = Item::findOrFail(Crypt::decrypt($encryptedId));

            $item->delete();
            return redirect()->route('items')->with('success', 'Barang berhasil dihapus.');
        } catch (DecryptException | ModelNotFoundException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal dihapus.');
        } catch (QueryException $e) {
            report($e);

            return back()->with('error', 'Barang gagal dihapus.');
        }
    }
}
