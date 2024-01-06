<?php

namespace App\Repository;

use App\Filters\CardFilter;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Device;
use App\Models\User;

use App\Models\Facility;

use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\matches;

class FacilityRepository implements Interfaces\DeviceRepositoryInterface
{
//    public CardFilter $filter;

    public function all()
    {
        return Facility::all();
    }

    public function forApi()
    {
/*        return Facility::where('user_id', Auth::id())
            ->get()
            ->map(function ($item, $key) {
            return ['label' => $item->name, 'value'=>$item->id];
        });*/
	$user = User::find(Auth::id());
	return $user->facilities()
            ->get()
            ->map(function ($item, $key) {
            return ['label' => $item->name, 'value'=>$item->id];
        });
    }

    /**
     * @param int $id
     * @return string
     */
    public function getName( int $id ) : string
    {
        $facility = Facility::where('id', $id)->first();
        if( $facility ) {
            return $facility->name;
        }
        return '';
    }

}
