<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $items = Item::query()
            ->search($search)
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        $view = $request->ajax() ? 'items.partials.table' : 'items.index';

        return response()->view($view, compact('items', 'search'))->header('Vary', 'X-requested-with');
    }

    public function store(ItemRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $item = Item::create($request->itemData());

                $item->movements()->create([
                    'type' => 'initial',
                    'quantity' => $item->quantity,
                    'created_by' => Auth::id(),
                ]);
            });

            // return redirect()->route('items')->with('success', 'Barang berhasil ditambahkan.');
            return $this->respond($request, 'Barang berhasil ditambahkan.', 201);
        } catch (UniqueConstraintViolationException $e) {
            report($e);
            throw ValidationException::withMessages(['kode' => 'Kode barang sudah digunakan.']);
        } catch (QueryException $e) {
            report($e);

            // return back()->withInput()->with('error', 'Barang gagal disimpan.');
            return $this->respond($request, 'Barang gagal disimpan.', 500);
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

            // return redirect()->route('items')->with('success', 'Barang berhasil diperbarui.');
            return $this->respond($request, 'Barang berhasil diperbarui', 200);
        } catch (DecryptException | ModelNotFoundException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal diperbarui.');
        } catch (UniqueConstraintViolationException $e) {
            report($e);
            throw ValidationException::withMessages(['kode' => 'Kode barang sudah digunakan.']);
        } catch (QueryException $e) {
            report($e);

            // return back()->with('error', 'Barang gagal diperbarui.');
            return $this->respond($request, 'Barang gagal diperbarui.', 500);
        }
    }

    public function destroy(Request $request, string $encryptedId)
    {
        try {
            $item = Item::findOrFail(Crypt::decrypt($encryptedId))->delete();

            return redirect()->route('items')->with('success', 'Barang berhasil dihapus.');
        } catch (DecryptException | ModelNotFoundException $e) {
            return back()->with('error', 'Barang tidak ditemukan & gagal dihapus.');
        } catch (QueryException $e) {
            report($e);

            return $this->respond($request, 'Barang gagal dihapus.', 500);
        }
    }

    private function respond(Request $request, string $message, int $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message
            ], $status);
        }

        return $status < 400
            ? redirect()->route('items')->with('success', $message)
            : back()->withInput()->with('error', $message);
    }
}
