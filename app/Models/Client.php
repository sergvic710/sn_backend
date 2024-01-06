<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date_own',
        'date_check',
        'location_id',
        'personal_number',
        'device_id',
        'comment'
    ];
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function device() {
        return $this->belongsTo(Device::class);
    }
    public function getDateOwnAttribute($value) {
        return ($value) ? (new Carbon($value))->format('d.m.Y') : '';
    }
}
