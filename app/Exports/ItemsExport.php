<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport extends StringValueBinder implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithCustomValueBinder,
    WithStrictNullComparison
{
    private int $rowNumber = 0;

    public function __construct(private ?string $search = null)
    {
        $this->setNumericConversion(false);
    }

    public function query(): Builder
    {
        return Item::query()
            ->search($this->search)
            ->orderBy('code')
            ->select(['code', 'product_name', 'product_category', 'quantity']);
    }

    public function headings(): array
    {
        return ['No', 'Kode', 'Nama Barang', 'Kategori', 'Stock'];
    }

    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            $item->code,
            $item->product_name,
            $item->product_category,
            (int) $item->quantity,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]], // baris 1 = heading
        ];
    }
}
