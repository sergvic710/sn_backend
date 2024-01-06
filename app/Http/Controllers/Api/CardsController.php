<?php

namespace App\Http\Controllers\Api;

use App\Filters\CardFilter;
use App\Filters\DeviceListFilter;
use App\Http\Requests\CardtExportRequest;
use App\Http\Requests\CardtRequest;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\DeviceListExportRequest;
use App\Http\Requests\DeviceListRequest;
use App\Http\Resources\CardResource;
use App\Http\Resources\DeviceResource;
use App\Models\Facility;
use App\Models\User;
use App\Repository\CardRepository;
use App\Repository\DeviceRepository;
use App\Repository\FacilityRepository;
use App\Services\ExportCards;
use App\Services\ExportDevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class CardsController extends BaseController
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CardtRequest $request, CardFilter $filter, CardRepository $repository,  FacilityRepository $facilityRepository) {
        $filter->make($request);
        $repository->setFilter($filter);
        $cards = $repository->paginate();
//        $devices = Device::paginate($pagination['pagesize']);
        $response = [
            'success' => true,
            'data' => CardResource::collection($cards),
            'lastPage' => $cards->lastPage(),
            'total' => $cards->total(),
            'facilities' =>  $facilityRepository->forApi(),
            'facilityval' => $filter->facilityVal,
            'facilityname' => $facilityRepository->getName($filter->facilityVal)
//            'message' => $message,
        ];

        return response()->json($response, 200);
//        return $this->sendResponse(DeviceResource::collection($devices), $devices->lastPage(), $devices->total(), 'Products retrieved successfully.');
    }
    public function save( ClientRequest $request, CardRepository $repository ) {
        $repository->save($request);
        return response()->json([
            'success' => true], 200);
    }
    public function export(
        CardtExportRequest $request,
        CardFilter $filter,
        CardRepository $repository,
    ) {
        $filter->make($request);
        $repository->setFilter($filter);
        $cards = $repository->all();
        if( Excel::store(new ExportCards($cards), 'public/cards.xlsx') ) {
            return response()->json(['file' => '/storage/cards.xlsx']);
        }
        //return response()->json($response, 200);
//        return $this->sendResponse(DeviceResource::collection($devices), $devices->lastPage(), $devices->total(), 'Products retrieved successfully.');
    }
    public function getColumns( ) {
        $userId = Auth::id();
        $user = User::find($userId);
        $arrConfig = json_decode($user->columns_config);
        $data = $arrConfig->columnCards ?? [];
        return response()->json(['columnCards' => $data]);
    }
    public function saveColumns( Request $request ) {
        $data = $request->all();
        if( isset($data['columnCards'])) {
            $userId = Auth::id();
            $user = User::find($userId);
            $arrConfig = json_decode($user->columns_config, true);
            $arrConfig['columnCards'] = $data['columnCards'];
            $user->columns_config = json_encode($arrConfig);
            $user->save();
        }
    }
}
