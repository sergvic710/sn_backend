<?php

namespace App\Repository;

use App\Filters\CardFilter;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Device;

use App\Models\Facility;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\matches;

class CardRepository implements Interfaces\DeviceRepositoryInterface
{
    public CardFilter $filter;
    private $query;

    public function paginate(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $this->buildQuery();
        if ($this->filter->pageSize == -1) {
            $pageSize = $this->query->get()->count();
            return $this->query->paginate($pageSize);
        } else {
            return $this->query->paginate($this->filter->pageSize);
        }
    }

    public function buildQuery()
    {
        $facility = Facility::where('user_id', Auth::id())->first();
//        $this->query = Client::with('location');
        $this->query = Client::select(['clients.*']);
        if ($this->filter->filterInputVal) {
            switch ($this->filter->filterOptionVal) {
                case 'flat':
                    $this->query = $this->query->whereHas('location', function ($query) {
                        $query->where('flat', $this->filter->filterInputVal);
                    });
                    break;
                case 'id':
                    $this->query->where('number', $this->filter->filterInputVal);
                    break;
                case 'personal_number':
                    $this->query = $this->query->whereHas('client', function ($query) {
                        $query->where('personal_number', $this->filter->filterInputVal);
                    });
                    break;
                case 'code':
                    $this->query->where('code', $this->filter->filterInputVal);
                    break;
            }
        }
        $this->query = $this->query->whereHas('location', function ($query) {
            $query->where('facility_id', $this->filter->facilityVal);
        });

        $this->query->join('locations', 'clients.location_id', '=', 'locations.id')
            ->groupBy( 'location_id')
            ->orderBy('flat');
    }

    public function all(): Collection
    {
        $this->buildQuery();
        return $this->query->get();
    }

    /**
     * @param $filter
     * @return void
     */
    public function setFilter($filter): void
    {
        $this->filter = $filter;
    }

    public function save(ClientRequest $request)
    {
        $data = $request->all();
        $client = Client::find($data['id']);
        if (!$data['name']) {
            $data['name'] = '';
        }
        $client->name = $data['name'];
        $dateCheck = (isset($data['datecheck'])) ? \Carbon\Carbon::createFromFormat(
            'd.m.Y',
            $data['datecheck']
        )->setTimezone(
            'Europe/Moscow'
        )->format('Y-m-d H:i:s') : null;
        $dateOwn = (isset($data['dateown'])) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data['dateown'])->setTimezone(
            'Europe/Moscow'
        )->format('Y-m-d H:i:s') : null;
        $client->device->date_check = $dateCheck;
        $client->device->save();
        $client->date_own = $dateOwn;
        if (!$data['comment']) {
            $data['comment'] = '';
        }
        $client->comment = $data['comment'];
        $client->save();
        $aa = 10;
    }
}
