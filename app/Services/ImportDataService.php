<?php

namespace App\Services;

use App\Mail\CheckData;
use App\Models\Client;
use App\Models\Device;
use App\Models\DeviceParameter;
use App\Models\Facility;
use App\Models\ImportConfig;
use App\Models\Location;
use App\Models\Parameter;
use App\Repository\DeviceParameterRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $date->setTime(0,0,);
//            $date->sub(new \DateInterval('P1D'));
        } else {
            $date = new \DateTime($this->dateStart);
        }
        Log::channel('import')->info(
            'Начинаем загрузку данных за ' . $date->format('d-m-Y') . ' за ' . $days . ' дней'
        );
        $isData = DeviceParameterRepository::checkData($date->format('Y-m-d'));
        if( $isData ) {
            Log::channel('import')->info(
                'Данные за ' . $date->format('d-m-Y') . ' уже есть в базе. Прекращаем загрузку.'
            );
            return;
        }
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
            'date' => $date,
            'facility' => $facilityCode,
            'counter' => $config->counter,
            'isWater' => $config->is_water
        ];
        Log::channel('import')->info(
            'Запрос: ' . json_encode($arrRequest)
        );
        $response = Http::withBody(
            json_encode($arrRequest)
        )->post('http://79.143.29.9:3000/getData');
        if ($response->status() == 200 && !empty($response->json()['data'])) {
//        if (true) {
            $data = $response->json()['data'];
//            if ($config->counter == 1) {
//                $data = json_decode(
//                    '
//[
//{
//            "id": 17796,
//            "code": 6,
//            "counter": "Тариф 1",
//            "data": 741.18,
//            "date": 1690146000000,
//            "location": "ул.Мосфильмовская, д.98кор1, кв.6",
//            "personal_number": "0076000601",
//            "status": 1,
//            "serial": "43198531",
//            "tariff_uid": "t1"
//        },
//        {
//            "id": 17797,
//            "code": 1006,
//            "counter": "Тариф 2",
//            "data": 795.28,
//            "date": 1690146000000,
//            "location": "ул.Мосфильмовская, д.98кор1, кв.6",
//            "personal_number": "0076000601",
//            "status": 1,
//            "serial": "43198531",
//            "tariff_uid": "t2"
//        },
//        {
//            "id": 17798,
//            "code": 2006,
//            "counter": "Тариф 3",
//            "data": 1069.21,
//            "date": 1690146000000,
//            "location": "ул.Мосфильмовская, д.98кор1, кв.6",
//            "personal_number": "0076000601",
//            "status": 1,
//            "serial": "43198531",
//            "tariff_uid": "t3"
//        },
//        {
//            "id": 17799,
//            "code": 3006,
//            "counter": "Тариф 4",
//            "data": 0,
//            "date": 1690146000000,
//            "location": "ул.Мосфильмовская, д.98кор1, кв.6",
//            "personal_number": "0076000601",
//            "status": 1,
//            "serial": "43198531",
//            "tariff_uid": "t4"
//        }
//]
//            ',
//                    true
//                );
//            } elseif ($config->counter == 3) {
//                $data = json_decode(
//                    '
//[
// {
//            "id": 9498,
//            "code1": 0,
//            "code2": 0,
//            "code3": 0,
//            "code4": 0,
//            "code5": 0,
//            "code6": 0,
//            "code7": 0,
//            "code8": 0,
//            "data_energy": 11.162577,
//            "data_in1": 0,
//            "data_in2": 0,
//            "data_in3": 0,
//            "data_in4": 0,
//            "data_in5": 0,
//            "data_in6": 0,
//            "data_in7": 0,
//            "data_in8": 0,
//            "date": 1690146000000,
//            "location": "ул. Мосфильмовская, д. 98кор1, кв.6",
//            "mc_number": "1-2-6",
//            "personal_number": "0076000601",
//            "serial_in1": "0",
//            "serial_in2": "0",
//            "serial_in3": "0",
//            "serial_in4": "0",
//            "serial_in5": "0",
//            "serial_in6": "0",
//            "serial_in7": "0",
//            "serial_in8": "0",
//            "status": 1,
//            "serial": "0"
//        },
//        {
//            "id": 9962,
//            "code1": 0,
//            "code2": 0,
//            "code3": 0,
//            "code4": 0,
//            "code5": 0,
//            "code6": 0,
//            "code7": 0,
//            "code8": 0,
//            "data_energy": 0,
//            "data_in1": 59.18,
//            "data_in2": 12.69,
//            "data_in3": 0,
//            "data_in4": 0,
//            "data_in5": 0,
//            "data_in6": 0,
//            "data_in7": 0,
//            "data_in8": 0,
//            "date": 1690146000000,
//            "location": "ул. Мосфильмовская, д. 98кор1, кв.6",
//            "mc_number": "2-2-6",
//            "personal_number": "0076000601",
//            "serial_in1": "20-3992252",
//            "serial_in2": "20-3992271",
//            "serial_in3": "0",
//            "serial_in4": "0",
//            "serial_in5": "0",
//            "serial_in6": "0",
//            "serial_in7": "0",
//            "serial_in8": "0",
//            "status": 1,
//            "serial": "0"
//        }
//]
//            ',
//                    true
//                );
//            } else {
//                return;
//            }
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

//                if ($config->is_water) {
//                    $item['data'] = 0;
//                    foreach (array_keys($item) as $field) {
//                        if (stripos($field, 'data_in') !== false && !empty($item[$field])) {
//                            $item['data'] += $item[$field];
//                        }
//                    }
//                } elseif (in_array($config->counter, [3, 4])) {
//                    $item['data'] = $item['data_energy'];
//                }
//                if (empty($item['data'])) {
//                    $item['data'] = 0;
//                }

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

//                    $device = Device::updateOrCreate(
//                        $deviceSyncParams,
//                        $deviceSaveParams
//                    );
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
//                    if ($item['status']) {
//                        $dataParameter = Parameter::where('name', 'data')->first();
//                        $dataExist = DeviceParameter::where('date', $date)
//                            ->where('device_id', $device->id)
//                            ->where('parameter_id', $dataParameter->id)
//                            ->where('date', $date)
//                            ->first();
//                        if( $dataExist ) {
//                            $dataExist->value =  $item['data'];
//                            $dataExist->save();
//                        } else {
//                            $device->parameters()->attach([
//                                $dataParameter->id => [
//                                    'value' => $item['data'],
//                                    'date' => $date,
//                                    'created_at' => (new Carbon())->format('Y-m-d H:i:s')
//                                ]
//                            ]);
//                        }
//                    }
                }
            }
        }
    }

    static public function checkData( $dateStart, $sendmail)
    {
        if ($dateStart == 'текущая') {
            $date = new \DateTime();
            $date->setTime(0,0,);
        } else {
            $date = new \DateTime($dateStart);
        }
        Log::channel('import')->info(
            'Проверяем данные за ' . $date->format('d-m-Y')
        );
        $isData = DeviceParameterRepository::checkData($date->format('Y-m-d'));
        if( !$isData && $sendmail) {
            Log::channel('import')->info(
                'Данных за ' . $date->format('d-m-Y') . ' в базе нет. Отправляем письмо.'
            );
            Mail::send( new CheckData($date->format('d-m-Y')));
        } else {
            Log::channel('import')->info(
                'Данных за ' . $date->format('d-m-Y') . ' в базе есть.'
            );
        }
    }

}
