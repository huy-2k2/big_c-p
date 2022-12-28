<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class ExcelsExport implements FromCollection, WithMultipleSheets, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, 
WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $sheets;
    public $index;
    public $headings;
    public $company_name = 'Product';
    public function __construct($sheets = [], $headings = [], $index = 0)
    {
        $this->sheets = $sheets;
        $this->index = $index;
        $this->headings = $headings;
    }


    public function setSheets($sheets = []) {
        $this->sheets = $sheets;
    }

    public function setIndex($index = 0) {
        $this->index = $index;
    }

    public function setHeadings($headings = []) {
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->sheets[$this->index];
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function sheets(): array
    {
        $sheets = [];

        for ($index = 0; $index < count($this->sheets); $index++) {
            $sheets[] = new ExcelsExport($this->sheets, $this->headings, $index);
        }
        return $sheets;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(14);
    }

    public function title(): string
    {
        return $this->company_name;
    }

    public function map($row): array{
        return [
            Date::dateTimeToExcel($row->datum),
        ];
    }

    public function columnFormats(): array {
        return [
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }
}
