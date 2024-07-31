<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilteredExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $config;

    public function __construct($data, $config)
    {
        $this->data = $data;
        $this->config = $config;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return array_keys($this->config['files'][array_key_first($this->config['files'])]['headers_to_db']);
    }
}
