<?php

namespace App\Filters;

use App\Http\Requests\DeviceListRequest;
use App\Models\Device;
use App\Models\DeviceParameter;
use App\Models\DevicePlace;
use App\Models\DeviceType;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;

class DeviceListFilter implements Interfaces\DeviceFilterInterface
{
    public int $page;
    public int $pageSize;
    public string|null $filterOptionVal;
    public int|null $filterInputVal;
    public int|null $facilityVal;
    public string|null $dateFrom = null;
    public string|null $dateTo = null;
    public string|null $deviceTypeVal;
    public string|null $devicePlaceVal;
    public int|null $status = null;
    public string|null $sorterOrder;
    public string|null $sorterColumnKey;


    /**
     * @param DeviceListRequest $request
     * @return void
     */
    public function make( $request)
    {
        $fields = $request->validated();
        $this->page = $fields['page'] ?? 1;
        $this->pageSize = $fields['pagesize'] ?? 25;
        $this->filterOptionVal = isset($fields['filteroptionval']) ? $fields['filteroptionval'] : null;
        $this->filterInputVal =  isset($fields['filterinputval']) ? $fields['filterinputval'] : null;
        $this->facilityVal = isset($fields['facilityval']) ? $fields['facilityval'] : null;
        if (!$this->facilityVal) {
            $facility = Facility::where('user_id', Auth::id())->first();
            if ($facility) {
                $this->facilityVal = $facility->id;
            }
        }
        $this->status = isset($fields['status']) ? $fields['status'] : null;
        $this->deviceTypeVal = isset($fields['devicetypeval']) ? $fields['devicetypeval'] : 1;
        $this->devicePlaceVal = isset($fields['deviceplaceval']) ? $fields['deviceplaceval'] : null;
        if( isset($fields['daterange']) && $fields['daterange'] != '') {
            if(str_contains($fields['daterange'], 'по')) {
                $date = explode('по',$fields['daterange']);
                $this->dateFrom = (new \DateTime($date[0]))->format('Y-m-d');
                $this->dateTo = (new \DateTime($date[1]))->format('Y-m-d');
            } else {
                $this->dateFrom = (new \DateTime($fields['daterange']))->format('Y-m-d');
            }
        } else {
            $parametersValue = DeviceParameter::orderBy('date', 'DESC')->first();
            $fields['daterange'] = $parametersValue->date;
            $this->dateFrom = (new \DateTime($fields['daterange']))->format('Y-m-d');
        }
        $this->sorterOrder = isset($fields['sorterorder']) ? $fields['sorterorder'] : null;
        $this->sorterColumnKey = isset($fields['sortercolumnkey']) ? $fields['sortercolumnkey'] : null;
    }

    /**
     * @return string
     */
    public function getExportFileName() : string
    {
        $arName = [];
        if( $this->deviceTypeVal ) {
           $type = DeviceType::where('id', $this->deviceTypeVal)->first();
           if( $type ) {
               $arName [] = $type->name;
           }
        }
        if( $this->devicePlaceVal ) {
            $place = DevicePlace::where('id', $this->devicePlaceVal)->first();
            if( $place ) {
                $arName [] = $place->name;
            } else {
                $arName [] = 'Все';
            }
        } else {
            $arName [] = 'Все';
        }
        if( $this->facilityVal ) {
            $facility = Facility::where('user_id', Auth::id())->first();
            if ($facility) {
                $arName [] = $facility->name;
            }
        }
        if(  $this->dateFrom ) {
            $arName [] = (new \DateTime($this->dateFrom))->format('d-m-Y');
//            $arName [] = (new \DateTime($this->dateTo))->format('d-m-Y');
        } else {
            $arName [] = 'Последние показания';
        }
        return implode('_', $arName);
    }
}
