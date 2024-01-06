<?php

namespace App\Repository;

use App\Filters\CardFilter;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Device;

use App\Models\Facility;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\matches;

class UserRepository implements Interfaces\DeviceRepositoryInterface
{

    public function all()
    {
    }

    /**
     * @param $filter
     * @return void
     */

    public function exportType( ) {
        $user = User::find(Auth::id());
        return $user->typeEXport->type;
    }
}
