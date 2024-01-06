<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Device;
use App\Models\DeviceData;
use App\Models\DeviceParameter;
use App\Models\DeviceType;
use App\Models\Facility;
use App\Models\ImportConfig;
use App\Models\Location;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportDataService
{
    public function __construct(protected string $dateStart, protected int $days)
    {
    }

    public function start()
    {
        $days = $this->days;
        if ($this->dateStart == 'текущая') {
            $date = new \DateTime();
            $date = new \DateTime('08/16/2023s 12:00 PM');
//            $date->sub(new \DateInterval('P1D'));
        } else {
            $date = new \DateTime($this->dateStart);
        }
        Log::channel('import')->info(
            'Начинаем загрузку данных зв ' . $date->format('d-m-Y') . ' за ' . $days . ' дней'
        );
        do {
            // 1663102800000
            // 1663189200000
            // 1690491600000
            // 1689454800000
            $milisec = $date->format('Uv');
            $facilities = Facility::all();
            $facilities = Facility::where('code', 21)->get();
            $configs = ImportConfig::where('is_active', 1)->get();
            foreach ($facilities as $facility) {
                foreach ($configs as $config) {
                    Log::channel('import')->info(
                        'Загружаем данные объекта ' . $facility->name . '. Код :' . $facility->code
                    );
                    $this->readData($milisec, $facility->id, $facility->code, $config);
                }
            }
            $date->add(new \DateInterval('P1D'));
            $days--;
        } while ($days != 0);
    }

    private function readData(string $date, int $facilityId, int $facilityCode, ImportConfig $config)
    {
        $arrRequest = [
            'date' => 1692046800000,
            'facility' => $facilityCode,
            'counter' => $config->counter,
            'isWater' => $config->is_water
        ];
        $response = Http::withBody(
            json_encode($arrRequest)
        )->post('http://79.143.29.9:3000/getData');
        Log::channel('import')->info(
            $arrRequest
        );
        Log::channel('import')->info(
            $response
        );
        if ($response->status() == 200 && !empty($response->json()['data'])) {
//        if (true) {
            $data = $response->json()['data'];
            Log::channel('import')->info('Получили ' . count($data) . 'записей');
            $hasHot = [];
            foreach ($data as $item) {
                Log::channel('import')->info('Данные {data}', ['data' => $item]);
                if (empty($item['code'])) {
                    $item['code'] = 0;
                }
                if (empty($item['personal_number'])) {
                    $item['personal_number'] = 0;
                }
                if (empty($item['counter'])) {
                    $item['counter'] = $config->counter;
                }
                if (empty($item['serial'])) {
                    $item['serial'] = 0;
                }
                $parametersValue = [];


                $location = $item['location'];
                preg_match('/кв. (\d+)$/m', $location, $matches);
                if (!isset($matches[1])) {
                    preg_match('/кв.(\d+)$/m', $location, $matches);
                }
                if (isset($matches[1])) {
//                    $aa = \DateTime::createFromFormat('U', $item['date'] / 1000, new \DateTimeZone('Europe/Moscow'));
                    $flat = $matches[1];
                    $date = \Carbon\Carbon::createFromFormat('U', $item['date'] / 1000)->setTimezone(
                        'Europe/Moscow'
                    )->format('Y-m-d H:i:s');
                    $location = Location::firstOrCreate([
                        'address' => $location
                    ],
                        [
                            'flat' => $flat,
                            'facility_id' => $facilityId,
                        ]);

                    $parameters = Parameter::where('device_type_id', $config->device_type_id)->get();
                    $deviceSyncParams = [];
                    $deviceSaveParams = [];
                    $isWater = false;
//                    $devices = [];
                    // Смотрим тепловые и водяные считчики, они приходят в одном ответе
                    // сначала тепловой потом водяной
                    if (array_key_exists($location->id, $hasHot) == false) {
                        $hasHot[$location->id] = $location->id;
                    } else {
                        $isWater = true;
                    }
                    if ($parameters) {
                        foreach ($parameters as $param) {
                            if (array_key_exists($param->name, $item) !== false) {
                                if ($config->counter == 1) {
                                    $device = Device::updateOrCreate([
                                        'code' => $item['code'],
                                        'device_type_id' => $config->device_type_id,
                                        'device_place_id' => $config->device_place_id,
                                        'location_id' => $location->id,
                                    ],
                                        $deviceSaveParams = [
                                            'number' => $item['serial'],
                                            'counter' => $item['counter'],
                                            'last_answer' => ($item['status'] == 1) ? $date : null,
                                            'status' => $item['status'],
                                            'personal_number' => $item['personal_number'],
                                            'raw_data' => json_encode($item)
                                        ]);
                                    $parametersValue [$param->id] = [
                                        'device' => $device,
                                        'value' => $item[$param->name]
                                    ];
                                    $client = Client::firstOrCreate(
                                        [
                                            'device_id' => $device->id
                                        ],
                                        [
                                            'location_id' => $location->id,
                                        ]
                                    );
                                } elseif ($config->counter == 3) {
                                    if (!$isWater && $config->is_water == 0) {
                                        // Если в массиве нет записи для адреса, считаем что это запись для теплового считчика
                                        $device = Device::updateOrCreate([
                                            'device_type_id' => 2,
                                            'device_place_id' => $config->device_place_id,
                                            'location_id' => $location->id,
                                        ],
                                            [
                                                'code' => $item['code'],
                                                'number' => $item['serial'],
                                                'counter' => $item['counter'],
                                                'last_answer' => ($item['status'] == 1) ? $date : null,
                                                'status' => $item['status'],
                                                'personal_number' => $item['personal_number'],
                                                'raw_data' => json_encode($item)
                                            ]);
                                        $parametersValue [$param->id] = [
                                            'device' => $device,
                                            'value' => $item[$param->name]
                                        ];
                                        $client = Client::firstOrCreate(
                                            [
                                                'device_id' => $device->id
                                            ],
                                            [
                                                'location_id' => $location->id,
                                            ]
                                        );
                                    } elseif ($isWater && $config->is_water == 1) {
                                        // Если запись есть, то это вода
                                        preg_match('/(\d)+$/', $param->name, $matches);
                                        $index = $matches[0];
                                        $device = Device::updateOrCreate([
                                            'device_type_id' => 3,
                                            'device_place_id' => $config->device_place_id,
                                            'location_id' => $location->id,
                                            'number' => $item['serial_in' . $index],
                                        ],
                                            [
                                                'code' => $item['code'],
                                                'counter' => $item['counter'],
                                                'last_answer' => ($item['status'] == 1) ? $date : null,
                                                'status' => $item['status'],
                                                'personal_number' => $item['personal_number'],
                                                'raw_data' => json_encode($item)
                                            ]);
                                        $parametersValue [$param->id] = [
                                            'device' => $device,
                                            'value' => $item[$param->name]
                                        ];
                                        $client = Client::firstOrCreate(
                                            [
                                                'device_id' => $device->id
                                            ],
                                            [
                                                'location_id' => $location->id,
                                            ]
                                        );
                                    }
                                }
                            }
                        }
                    }


                    if ($item['status'] && $parametersValue) {
                        foreach ($parametersValue as $paramId => $param) {
                            $dataExist = DeviceParameter::where('date', $date)
                                ->where('device_id', $param['device']->id)
                                ->where('parameter_id', $paramId)
                                ->where('date', $date)
                                ->first();
                            if ($dataExist) {
                                $dataExist->value = $param['value'];
                                $dataExist->save();
                            } else {
                                $param['device']->parameters()->attach([
                                    $paramId => [
                                        'value' => $param['value'],
                                        'date' => $date,
                                        'created_at' => (new Carbon())->format('Y-m-d H:i:s')
                                    ]
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

}
