<?php

namespace App\Repository;

use App\Filters\DeviceListFilter;
use App\Models\Device;

use App\Models\DeviceParameter;
use App\Models\Facility;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\matches;

class DeviceRepository implements Interfaces\DeviceRepositoryInterface
{
    public DeviceListFilter $filter;
    private $query;
    private string $facilityName;

    public function paginate() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if( $this->filter->dateTo ) {
            // Если запрос интервала
            // Сохраняем старое значение dateFrom. Заменяем на dateTo. Получаем данные за dateTo
            $oldDateFrom = $this->filter->dateFrom;
            $this->filter->dateFrom = $this->filter->dateTo;
            $this->buildQuery();
            if ($this->filter->pageSize == -1) {
                $pageSize = $this->query->get()->count();
                $dataTo = $this->query->paginate($pageSize);

                $this->filter->dateFrom = $oldDateFrom;
                $this->buildQuery();
                $pageSize = $this->query->get()->count();
                $dataTo = $this->query->paginate($pageSize);
            } else {
                $dataTo = $this->query->paginate($this->filter->pageSize);

                $this->filter->dateFrom = $oldDateFrom;
                $this->buildQuery();
                $dataFrom = $this->query->paginate($this->filter->pageSize);
                if( $dataFrom->count() > 0  && $dataTo->count() > 0 ) {
                    $itemsFrom = new Collection($dataFrom->items());
                    foreach ( $dataTo->items() as &$itemTo ) {
                        $index = $itemsFrom->search(function ( $item, $key ) use($itemTo) {
                            return $item->number === $itemTo->number && $item->counter === $itemTo->counter;
                        });
                        if( $index !== false ) {
                            $itemTo->dataFrom = $itemsFrom->get($index);
                        }
                    }
                }
                return $dataTo;
            }
        } else {
            $this->buildQuery();
            if ($this->filter->pageSize == -1) {
                $pageSize = $this->query->get()->count();
                return $this->query->paginate($pageSize);
            } else {
                return $this->query->paginate($this->filter->pageSize);
            }
        }
    }

    public function buildQuery() {
        $this->query = Device::select('devices.*');
//        $this->query = Device::joinRelationship('value');
//        $this->query = Device::with('client','deviceType','devicePlace');


//        $this->query = Device::join('location','client','deviceType','devicePlace');
//        $this->query =  Device::whereHas('location', function ($query) {
//            $query->where('facility_id', $this->filter->facilityVal);
//        });

//        $this->query->orderBy('location.flat');
        if ($this->filter->filterInputVal) {
            switch ($this->filter->filterOptionVal) {
                case 'flat':
                    $this->query =  $this->query->whereHas('location', function ($query) {
                        $query->where('flat', $this->filter->filterInputVal);
                    });
                    break;
                case 'id':
                    $this->query =  $this->query->where('number', $this->filter->filterInputVal);
                    break;
                case 'personal_number':
//                    $this->query =  $this->query->whereHas('client', function ($query) {
//                        $query->where('personal_number', $this->filter->filterInputVal);
//                    });
//                    break;
                    $this->query =  $this->query->where('personal_number', $this->filter->filterInputVal);
                    break;
                case 'code':
                    $this->query =  $this->query->where('code', $this->filter->filterInputVal);
                    break;
            }
        }
        if( $this->filter->dateFrom ) {
            $this->query->whereHas('parameters', $filterData = function ($query) {
                $query->where('date',$this->filter->dateFrom);
            });
            $this->query->with(['parameters' => $filterData]);
        }
//        $this->query->select([
//            'devices.id',
//            'number',
//            'code',
//            'personal_number',
//            'counter',
//            'devices.location_id',
//            'devices.device_type_id',
//            'device_parameter.id as device_parameter_id'
//        ]);
//        $this->query->join('device_parameter', 'devices.id', '=', 'device_parameter.device_id')
//            ->where('device_parameter.parameter_id',intval($this->filter->deviceTypeVal))
//            ->groupBy('devices.id');

        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'flat') {
            if( $this->filter->sorterOrder === 'descend') {
//                $this->query->join('locations', 'devices.location_id', '=', 'locations.id')
//                    ->orderBy('locations.flat','DESC');
                $this->query->joinRelationship('location')
                    ->orderByPowerJoins('location.flat', 'DESC');
            } else {
                $this->query->joinRelationship('location')
                    ->orderByPowerJoins('location.flat', 'ASC');
//                $this->query->join('locations', 'devices.location_id', '=', 'locations.id')
//                    ->orderBy('locations.flat','ASC');
            }
        }
        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'personal_number') {
            if( $this->filter->sorterOrder === 'descend') {
                $this->query->orderBy('personal_number','DESC');
            } else {
                $this->query->orderBy('personal_number','ASC');
            }
        }
        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'number') {
            if( $this->filter->sorterOrder === 'descend') {
                $this->query->orderBy('number','DESC');
            } else {
                $this->query->orderBy('number','ASC');
            }
        }
        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'code') {
            if( $this->filter->sorterOrder === 'descend') {
                $this->query->orderBy('code','DESC');
            } else {
                $this->query->orderBy('code','ASC');
            }
        }
        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'counter') {
            if( $this->filter->sorterOrder === 'descend') {
                $this->query->orderBy('counter','DESC');
            } else {
                $this->query->orderBy('counter','ASC');
            }
        }
//        $this->query = Device::('parameters');
        if( $this->filter->sorterOrder && $this->filter->sorterColumnKey === 'data') {
            $parameterId = intval($this->filter->deviceTypeVal);
            if( $this->filter->sorterOrder === 'descend') {
//                $this->query->orderBy('device_parameter.value','DESC');
                $this->query->join('device_parameter', 'devices.id', '=', 'device_parameter.device_id')
                    ->where('device_parameter.parameter_id',$parameterId)
                    ->where('date',$this->filter->dateFrom)
                    ->orderBy('device_parameter.value','DESC')
                    ->groupBy('devices.id');
            } else {
//                $this->query->orderBy('device_parameter.value','ASC');
                $this->query->join('device_parameter', 'devices.id', '=', 'device_parameter.device_id')
                    ->where('device_parameter.parameter_id',$parameterId)
                    ->where('date',$this->filter->dateFrom)
                    ->orderBy('device_parameter.value','ASC')
                    ->groupBy('devices.id');
            }
        }


//        if (!$this->filter->facilityVal) {
//            $facility = Facility::where('user_id', Auth::id())->first();
//            if( $facility ) {
//                $this->filter->facilityVal = $facility->id;
//            }
////            $this->query->whereHas('location', function ($query) use ($facility) {
////                $query->where('facility_id', $facility->id);
////            } );
//        }

        if ($this->filter->deviceTypeVal) {
            $this->query = $this->query->where('device_type_id', $this->filter->deviceTypeVal);
        }
        if ($this->filter->devicePlaceVal && $this->filter->devicePlaceVal != 3) {
            $this->query =  $this->query->where('device_place_id', $this->filter->devicePlaceVal);
        }
        if( !is_null($this->filter->status) ) {
            $this->query->where('status', $this->filter->status);
        }
//        $this->query->orderBy('personal_number');
//        $this->query->orderBy('locations.flat');
//        $this->paginate();
    }

    public function all() : Collection
    {
        $this->buildQuery();
        return $this->query->get();
    }

    public function getEnableDates() {
        if( !$this->query ) {
            $this->buildQuery();
        }
//        $this->query->groupBy('account_id')
    }
    /**
     * @param $filter
     * @return void
     */
    public function setFilter($filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @return DeviceListFilter
     */
    public function getFilter() : DeviceListFilter
    {
        return $this->filter;
    }
    public function getFacilityName() : int
    {
        return $this->facilityName;
    }

    /**
     * @param int $deviceId
     * @param string $tariffName
     * @return string
     */
    public function getTariffValue( int $deviceId, string $tariffName) : string
    {
//        $this->buildQuery();
//        $this->query->
        return '';
    }

    /**
     * @return Collection
     */
    public function makeExportList() : Collection
    {
        $items =new Collection();
        $this->buildQuery();
        $devices = $this->query->get();
        foreach ( $devices as $dev ) {
            if( $this->filter->deviceTypeVal === '1') {
                $number = $dev->number;
//                $item = $items->first(function ( $item, int $key) use ($number) {
//                    return $item == $number ;
//                });
                $item = $items->get($dev->number);
                if( !$item ) {
                    $item = [
                        'personal_number' => $dev->personal_number,
                        'serial' => $dev->number,
                        'address' => $dev->location->address
                    ];
                }
                switch ( $dev->counter ) {
                    case 'Тариф 1':
                        $item['tariff_1'] = $dev->parameters->first()?->pivot?->value;
                        break;
                    case 'Тариф 2':
                        $item['tariff_2'] = $dev->parameters->first()?->pivot?->value;
                        break;
                    case 'Тариф 3':
                        $item['tariff_3'] = $dev->parameters->first()?->pivot?->value;
                        break;
                    case 'Тариф 4':
                        $item['tariff_4'] = $dev->parameters->first()?->pivot?->value;
                        break;
                }
                $items->put(
                    $dev->number, $item
                );
            } elseif( $this->filter->deviceTypeVal === '2') {
                return $devices;
            }elseif( $this->filter->deviceTypeVal === '3') {
                return $devices;
            }
        }
        return $items;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function availableDate() : array
    {
        $dates = [];
        $allDates = DeviceParameter::select('date')
            ->groupBy('date')
            ->get();
        foreach ($allDates as $date ) {
            $dates [] = (new \DateTime($date->date))->format('d-m-Y');
        }
        return $dates;
    }
}
