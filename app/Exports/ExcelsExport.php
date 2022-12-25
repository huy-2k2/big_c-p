<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class ExcelsExport implements FromCollection, WithMultipleSheets, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $sheets;
    public $index;
    public $headings;
    public function __construct($sheets = [], $headings = [], $index = 0)
    {
        $this->sheets = $sheets;
        $this->index = $index;
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
}
