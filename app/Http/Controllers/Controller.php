<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Device;
use App\Models\DeviceData;
use App\Models\DeviceType;
use App\Models\Location;
use App\Services\ImportDataService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dataImport()
    {
        $import = new ImportDataService('24.07.2023',1);
        $import->start();
        die();
    }
}
