<?php

namespace App\Services;

use App\Filters\DeviceListFilter;
use App\Models\Device;
use App\Repository\DeviceRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportDevices implements FromCollection, WithMapping, WithHeadings
{

    public function __construct(
        private Collection       $items,
        private DeviceListFilter $filter)
    {
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        $heads = [];
        if ($this->filter->deviceTypeVal === '1') {
            $heads = [
                'Лицевой счёт',
                'Номер прибора',
                'T1',
                'T2',
                'T3',
                'T4',
                'Адрес',
            ];
        } elseif ($this->filter->deviceTypeVal === '2') {
            $heads = [
                'Лицевой счёт',
                'Номер прибора',
                'показания',
                'Адрес',
            ];
        } elseif ($this->filter->deviceTypeVal === '3') {
            $heads = [
                'Лицевой счёт',
                'Номер прибора',
                'Показания',
                'Адрес',
                'Тип',
            ];
        }
        return $heads;
//        return [
//            '№ кв',
//            'Адрес',
//            'ФИО',
//            'Номер лицевого счета',
//            'Номер прибора',
//            'Код идентификатора',
//            'Текущие показания',
//            'Тариф'
//        ];
    }

    public function map($item): array
    {
        if ($this->filter->deviceTypeVal === '1') {

          file_put_contents(__DIR__ . '/array.txt', print_r($item,true) );

            $row = [
                $item['personal_number'],
                $item['serial'],
                $item['tariff_1']?$item['tariff_1']:"0",
                $item['tariff_2']?$item['tariff_2']:"0",
                $item['tariff_3']?$item['tariff_3']:"0",
                $item['tariff_4']?$item['tariff_4']:"0",
                $item['address']
            ];
//            $this->repository
        } elseif ($this->filter->deviceTypeVal === '2') {

            $array = $item;




            $row = [
                $item->personal_number,
                json_decode($array['raw_data'],1)['serial'],
                $item->parameters->first()?->pivot?->value,
                $item->location->address,
            ];
        } elseif ($this->filter->deviceTypeVal === '3') {

            $array = $item;





            $row = [
                $item->personal_number,
                $item->number,
                $item->parameters->first()?->pivot?->value,
                $item->location->address,
                $item->parameters->first()?->title
            ];
        }
        return $row;
    }


}
