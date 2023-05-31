<?php

namespace App\Exports;

use App\Scrapy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ScrapyExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Scrapy::all();
    }

    public function headings(): array
    {
        return [
          '#',
          'URL',
          'Name',
          'Number',
          'Address',
          'City',
          'KVK'
        ];
    }
}
