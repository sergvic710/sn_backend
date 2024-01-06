<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['int','required'],
            'pagesize' => ['int','required'],
            'filteroptionval' => ['string','nullable'],
            'filterinputval' => ['string','nullable'],
            'facilityval' => ['int','nullable'],
            'daterange' => ['string','nullable'],
            'devicetypeval' => ['int','nullable'],
            'deviceplaceval' => ['int','nullable'],
            'status' => ['int','nullable'],
            'sorterorder' => ['string','nullable'],
            'sortercolumnkey' => ['string','nullable']
        ];
    }
}
