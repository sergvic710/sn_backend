<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExportDevicesNotRespond implements FromCollection, WithMapping, WithHeadings
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
            'Последний раз отвечал',
            'Последние показания',
            'Тип прибора',
            'Номер прибора',
            'Код идентификатора'
        ];
    }
    public function map($item): array
    {
        return [
            $item->location->flat,
            $item->location->facility->address,
            ($item->last_answer) ? (new Carbon($item->last_answer))->format('d.m.Y') : '',
            $item->parameters->first()?->pivot?->value,
            $item->deviceType->name,
            $item->number,
            $item->code,
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
