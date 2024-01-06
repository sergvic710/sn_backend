<?php

namespace App\Filters\Interfaces;

use App\Http\Requests\DeviceListRequest;
use Illuminate\Foundation\Http\FormRequest;

interface DeviceFilterInterface
{
    public function make($request);
}
