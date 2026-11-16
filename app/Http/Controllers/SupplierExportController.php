<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Http\Requests\ExportRequest;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SupplierExportController extends Controller
{
    private const PDF_MAX_ROWS = 1000;

    public function excel(ExportRequest $request): BinaryFileResponse
    {
        $filename = 'Daftar_Supplier_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SuppliersExport($request->keyword()), $filename);
    }

    public function pdf(ExportRequest $request): Response|RedirectResponse
    {
        $search = $request->keyword();

        $suppliers = Supplier::query()
            ->search($search)
            ->orderBy('supplier_name')
            ->limit(self::PDF_MAX_ROWS + 1)
            ->get([
                'supplier_name',
                'supplier_address',
                'pic_name',
                'pic_contact_number',
                'pic_contact_email',
            ]);

        if ($suppliers->count() > self::PDF_MAX_ROWS) {
            return back()->with('error', 'Data terlalu banyak untuk PDF (maks. '
                . self::PDF_MAX_ROWS . ' baris). Persempit pencarian atau gunakan export Excel.');
        }

        return Pdf::loadView('suppliers.exports.pdf', [
            'title'     => 'Daftar Supplier',
            'suppliers' => $suppliers,
            'total'     => $suppliers->count(),
            'search'    => $search,
            'printedAt' => now(),
        ])
            ->setPaper('a4', 'landscape')
            ->download('Daftar_Supplier_' . now()->format('Ymd_His') . '.pdf');
    }
}
