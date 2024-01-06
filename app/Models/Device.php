<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Kirschbaum\PowerJoins\PowerJoins;

class Device extends Model
{
    use HasFactory, PowerJoins;
    protected $fillable = [
        'code',
        'counter',
        'date_check',
        'last_answer',
        'last_answer',
        'location_id',
        'device_type_id',
        'device_place_id',
        'personal_number',
        'number',
        'status',
        'raw_data'
    ];
    protected $casts = [
        'date_check' => 'datetime:d.m.Y'
    ];

//    protected $table = 'devices';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deviceType() {
        return $this->belongsTo(DeviceType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devicePlace()
    {
        /**
         * device(place_id) -> device_places(id)
         */
        return $this->belongsTo(DevicePlace::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class)->orderBy('flat');
    }
    public function parameters() {
        /***
         *  device(id) -> device_data(device_id)
         */
        return $this->belongsToMany(Parameter::class)->withPivot('value', 'date', 'id');
    }
    public function value() {
        return $this->belongsToMany(DeviceParameter::class,'device_parameter');
    }
    public function client() {
        return $this->hasOne(Client::class);
    }
    public function getDateCheckAttribute($value) {
            return ($value) ? (new Carbon($value))->format('d.m.Y') : '';
    }
}
