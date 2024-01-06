<?php

namespace App\Http\Controllers\Api;

use App\Filters\DeviceListFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceListExportRequest;
use App\Http\Requests\DeviceListRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\Facility;
use App\Models\User;
use App\Repository\DeviceRepository;
use App\Repository\FacilityRepository;
use App\Services\ExportDevices;
use App\Services\ExportDevicesNotRespond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class DevicesController extends BaseController
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        DeviceListRequest $request,
        DeviceListFilter $filter,
        DeviceRepository $repository,
        FacilityRepository $facilityRepository
    ) {
        $filter->make($request);
        $repository->setFilter($filter);
        $devices = $repository->paginate();
//        $devices = Device::paginate($pagination['pagesize']);
        $facilities = $facilityRepository->forApi();
        if (!empty(($facilityVal = $repository->getFilter()->facilityVal))) {
            $facilityName = $facilityRepository->getName($repository->getFilter()->facilityVal);
        } else {
            $facility = $facilities->toArray()[0];
            $facilityVal = $facility['value'];
            $facilityName = $facility['label'];
        }
        $response = [
            'success' => true,
            'data' => DeviceResource::collection($devices),
            'lastPage' => $devices->lastPage(),
            'total' => $devices->total(),
            'facilities' => $facilities,
            'facilityval' => $facilityVal,
            'facilityname' => $facilityName,
            'availableDates' => $repository->availableDate()
//            'message' => $message,
        ];

        return response()->json($response, 200);
//        return $this->sendResponse(DeviceResource::collection($devices), $devices->lastPage(), $devices->total(), 'Products retrieved successfully.');
    }
    public function export(
        DeviceListExportRequest $request,
        DeviceListFilter $filter,
        DeviceRepository $repository,
    ) {
        $filter->make($request);
        $repository->setFilter($filter);
        $fileName = $filter->getExportFileName();
        if( $fileName ) {
            $fileName .= '.xlsx';
            $devices = $repository->makeExportList();
            if( Excel::store(new ExportDevices($devices, $filter), 'public/'.$fileName) ) {
                return response()->json(['file' => '/storage/'.$fileName]);
            }
        }
        //return response()->json($response, 200);
//        return $this->sendResponse(DeviceResource::collection($devices), $devices->lastPage(), $devices->total(), 'Products retrieved successfully.');
    }
    public function exportnotrespond(
        DeviceListExportRequest $request,
        DeviceListFilter $filter,
        DeviceRepository $repository,
    ) {
        $filter->make($request);
        $repository->setFilter($filter);
        $devices = $repository->all();
        if( Excel::store(new ExportDevicesNotRespond($devices), 'public/devices.xlsx') ) {
            return response()->json(['file' => '/storage/devices.xlsx']);
        }
        //return response()->json($response, 200);
//        return $this->sendResponse(DeviceResource::collection($devices), $devices->lastPage(), $devices->total(), 'Products retrieved successfully.');
    }
    public function getColumns( ) {
        $userId = Auth::id();
        $user = User::find($userId);
        $arrConfig = json_decode($user->columns_config);
        $data = $arrConfig->columnDevices ?? [];
        return response()->json(['columnDevices' => $data]);
    }
    public function saveColumns( Request $request ) {
        $data = $request->all();
        if( isset($data['columnDevices'])) {
            $userId = Auth::id();
            $user = User::find($userId);
            $arrConfig = json_decode($user->columns_config, true);
            $arrConfig['columnDevices'] = $data['columnDevices'];
            $user->columns_config = json_encode($arrConfig);
            $user->save();
        }
    }
    public function getColumnsNotRespond( ) {
        $userId = Auth::id();
        $user = User::find($userId);
        $arrConfig = json_decode($user->columns_config);
        $data = $arrConfig->columnNotRespond ?? [];
        return response()->json(['columnNotRespond' => $data]);
    }
    public function saveColumnsNotRespond( Request $request ) {
        $data = $request->all();
        if( isset($data['columnNotRespond'])) {
            $userId = Auth::id();
            $user = User::find($userId);
            $arrConfig = json_decode($user->columns_config, true);
            $arrConfig['columnNotRespond'] = $data['columnNotRespond'];
            $user->columns_config = json_encode($arrConfig);
            $user->save();
        }
    }
}
