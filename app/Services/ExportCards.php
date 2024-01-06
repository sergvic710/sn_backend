<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExportCards implements FromCollection, WithMapping, WithHeadings
{
    public function __construct( private Collection $items)
    {
    }

    public function collection() {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            '№ кв',
            'Адрес',
            'ФИО',
            'Дата поверки',
            'Дата вступления в собственность',
        ];
    }
    public function map($item): array
    {
        return [
            $item->location->flat,
            $item->location->address ?? '',
            $item->name ?? '',
            $item->client?->personal_number,
            $item->device->date_check,
            $item->date_own,
        ];
//        return [
//            $invoice->invoice_number,
//            Date::dateTimeToExcel($invoice->created_at),
//            $invoice->total
//        ];
    }

//    public function columnFormats(): array
//    {
//        return [
//            'A' => STR
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
//        ];
//    }
}
