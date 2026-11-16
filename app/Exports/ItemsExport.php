<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\BaseExport;

class ItemsExport extends BaseExport
{
    protected function columnHeadings(): array
    {
        return ['Kode', 'Nama Barang', 'Kategori', 'Stock'];
    }

    protected function mapRow(object $row): array
    {
        return [
            $row->code,
            $row->product_name,
            $row->product_category,
            (int) $row->quantity,
        ];
    }

    public function query(): Builder
    {
        return Item::query()
            ->search($this->search)
            ->orderBy('code')
            ->select(['code', 'product_name', 'product_category', 'quantity']);
    }
}
