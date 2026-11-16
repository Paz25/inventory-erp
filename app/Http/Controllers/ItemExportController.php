<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ItemExportController extends Controller
{
    private const PDF_MAX_ROWS = 1000;

    private function keyword(Request $request): ?string
    {
        $request->validate(['search' => ['nullable', 'string', 'max:100']]);

        $keyword = trim((string) $request->query('search', ''));

        return $keyword === '' ? null : $keyword;
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $filename = 'Daftar_Barang_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ItemsExport($this->keyword($request)), $filename);
    }

    public function pdf(Request $request): Response|RedirectResponse
    {
        $search = $this->keyword($request);

        $items = Item::query()
            ->search($search)
            ->orderBy('code')
            ->limit(self::PDF_MAX_ROWS + 1)
            ->get(['code', 'product_name', 'product_category', 'quantity']);

        if ($items->count() > self::PDF_MAX_ROWS) {
            return back()->with('error', 'Data overload (maks. '
                . self::PDF_MAX_ROWS . ' baris). Persempit pencarian atau gunakan export Excel.');
        }

        $pdf = Pdf::loadView('items.exports.pdf', [
            'title' => 'Daftar Barang',
            'items' => $items,
            'total' => $items->count(),
            'search' => $search,
            'printedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Daftar_Barang_' . now()->format('Ymd_His') . '.pdf');
    }
}
