<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     $movements = StockMovement::query()->when($search, function ($query, $search) {
    //         $query->whereHas('item', function ($itemQuery) use ($search) {
    //             $itemQuery->withTrashed()->where(function ($q) use ($search) {
    //                 $q->where('code', 'like', "%{$search}%")->orWhere('product_name', 'like', "%{$search}%");
    //             });
    //         });
    //     })->latest()->paginate(10)->withQueryString();

    //     $items = Item::query()->orderBy('product_name')->get(['id', 'code', 'product_name', 'quantity']);

    //     return view('stock-movements.index', compact('movements', 'items', 'search'));
    // }\
    public function index()
    {
        $items = Item::query()
            ->orderBy('product_name')
            ->get(['id', 'code', 'product_name', 'quantity']);

        return view('stock-movements.index', compact('items'));
    }

    public function data(Request $request): JsonResponse
    {
        $sortable = [
            1 => 'stock_movements.created_at',
            2 => 'items.code',
            3 => 'items.product_name',
            4 => 'stock_movements.type',
            5 => DB::raw("CASE WHEN stock_movements.type = 'keluar' THEN -stock_movements.quantity ELSE stock_movements.quantity END"),
        ];

        $start = max(0, $request->integer('start'));
        $length = min(100, max(1, $request->integer('length', 10)));
        $search = trim((string) $request->input('search.value', ''));

        $base = StockMovement::query()
            ->leftJoin('items', 'items.id', '=', 'stock_movements.item_id')
            ->select([
                'stock_movements.id',
                'stock_movements.type',
                'stock_movements.quantity',
                'stock_movements.created_at',
                'items.code as item_code',
                'items.product_name as item_name',
                'items.deleted_at as item_deleted_at',
            ]);

        $recordsTotal = (clone $base)->count();

        $query = (clone $base)->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('items.code', 'like', "%{$search}%")
                    ->orWhere('items.product_name', 'like', "%{$search}%");
            });
        });

        $recordsFiltered = (clone $query)->count();

        $orderColumn = $sortable[$request->integer('order.0.column')] ?? null;
        $orderDir = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';

        $orderColumn
            ? $query->orderBy($orderColumn, $orderDir)
            : $query->latest('stock_movements.created_at');

        $query->orderBy('stock_movements.id');

        $movements = $query->skip($start)->take($length)->get();

        $data = $movements->values()->map(fn(StockMovement $movement, int $i) => [
            'no' => $start + $i + 1,
            'created_at' => $movement->created_at->format('d M Y H:i'),
            'item_code' => e($movement->item_code ?? '-'),
            'item_name' => e($movement->item_name ?? '-'),
            'item_inactive' => $movement->item_deleted_at !== null,
            'type' => $movement->type,
            'quantity' => $movement->quantity,
        ]);

        return response()->json([
            'draw' => $request->integer('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StockMovementRequest $request)
    {
        try {
            $data = $request->stockMovementData();
            $data['created_by'] = Auth::id();

            $remainingStock = DB::transaction(function () use ($data) {
                $item = Item::whereKey($data['item_id'])->lockForUpdate()->firstOrFail();

                if ($data['type'] === 'keluar' && $item->quantity < $data['quantity']) {
                    throw ValidationException::withMessages([
                        'jumlah' => "Jumlah stok tidak mencukupi. Stok tersedia: {$item->quantity}.",
                    ]);
                }

                $item->movements()->create($data);

                $data['type'] === 'masuk'
                    ? $item->increment('quantity', $data['quantity'])
                    : $item->decrement('quantity', $data['quantity']);

                return $item->quantity;
            });

            // return redirect()->route('items')->with('success', 'Transaksi stok berhasil dicatat.');
            return $this->respond($request, 'Transaksi stok berhasil dibuat.', 201, [
                'remaining_stock' => $remainingStock,
            ]);
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages(['item_id' => 'Barang tidak ditemukan.']);
        } catch (QueryException $e) {
            report($e);

            return $this->respond($request, 'Transaksi stok gagal dibuat.', 500);
        }
    }

    private function respond(Request $request, string $message, int $status = 200, array $extra = [])
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                ...$extra,
            ], $status);
        }

        return $status < 400
            ? redirect()->route('stock-movements')->with('success', $message)
            : back()->withInput()->with('error', $message);
    }
}
