<?php

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use App\Exports\BaseExport;
use Illuminate\Database\Eloquent\Builder;

class SuppliersExport extends BaseExport
{
    public function query(): Builder
    {
        return Supplier::query()
            ->search($this->search)
            ->orderBy('supplier_name')
            ->orderBy('id')
            ->select([
                'id',
                'supplier_name',
                'supplier_address',
                'pic_name',
                'pic_contact_number',
                'pic_contact_email',
            ]);
    }

    protected function columnHeadings(): array
    {
        return ['Nama Perusahaan', 'Alamat', 'Nama PIC', 'No. Telp PIC', 'Email PIC'];
    }

    protected function mapRow(object $row): array
    {
        return [
            $row->supplier_name,
            $row->supplier_address,
            $row->pic_name,
            $row->pic_contact_number,
            $row->pic_contact_email,
        ];
    }
}
