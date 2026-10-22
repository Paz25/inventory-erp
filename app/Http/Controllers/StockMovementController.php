<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\NotEnoughStockException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = StockMovement::with('item')->latest()->paginate(10);

        $items = Item::query()->orderBy('product_name')->get(['id', 'code', 'product_name', 'quantity']);

        return view('stock-movements.index', compact('movements', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockMovementRequest $request)
    {
        try {
            $data = $request->stockMovementData();

            DB::transaction(function () use ($data) {
                $item = Item::whereKey($data['item_id'])->lockForUpdate()->firstOrFail();

                if ($data['type'] === 'keluar' && $item->quantity < $data['quantity']) {
                    throw new NotEnoughStockException($item->quantity);
                }
                $item->movements()->create($data);

                $data['type'] === 'masuk' ? $item->increment('quantity', $data['quantity']) : $item->decrement('quantity', $data['quantity']);
            });

            return redirect()->route('items')->with('success', 'Transaksi stok berhasil dicatat.');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Barang tidak ditemukan.');
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', 'Transaksi stok gagal disimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
