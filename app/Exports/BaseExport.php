<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport extends StringValueBinder implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithCustomValueBinder,
    WithStrictNullComparison
{
    private int $rowNumber = 0;

    public function __construct(protected ?string $search = null)
    {
        $this->setNumericConversion(false);
    }

    final public function headings(): array
    {
        return ['No', ...$this->columnHeadings()];
    }

    final public function map($row): array
    {
        return [++$this->rowNumber, ...$this->mapRow($row)];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    abstract protected function columnHeadings(): array;

    abstract protected function mapRow(object $row): array;
}
