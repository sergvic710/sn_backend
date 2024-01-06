<?php

namespace App\Filters;

use App\Http\Requests\CardtRequest;
use App\Http\Requests\DeviceListRequest;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;

class CardFilter implements Interfaces\DeviceFilterInterface
{
    public int $page;
    public int $pageSize;
    public string|null $filterOptionVal;
    public int|null $filterInputVal;
    public int|null $facilityVal;

    /**
     * @param CardtRequest $request
     * @return void
     */
    public function make( $request)
    {
        $fields = $request->validated();
        $this->page = $fields['page'] ?? 1;
        $this->pageSize = $fields['pagesize'] ?? 25;
        $this->filterOptionVal = isset($fields['filteroptionval']) ? $fields['filteroptionval'] : null;
        $this->filterInputVal =  isset($fields['filterinputval']) ? $fields['filterinputval'] : null;
        $this->facilityVal = isset($fields['facilityval']) ? $fields['facilityval'] : null;
        if (!$this->facilityVal) {
            $facility = Facility::where('user_id', Auth::id())->first();
            if ($facility) {
                $this->facilityVal = $facility->id;
            }
        }
    }
}
