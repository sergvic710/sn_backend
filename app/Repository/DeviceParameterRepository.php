<?php

namespace App\Repository;

use App\Models\DeviceParameter;

class DeviceParameterRepository
{
    /**
     * @param string $date Date
     * @return bool
     */
    static public function checkData( string $date ) : bool
    {
        $isData = DeviceParameter::where('date', $date)->get()->count();
        if( $isData ) {
            return true;
        }
        return false;
    }
}
