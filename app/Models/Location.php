<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'address',
        'flat',
        'facility_id'
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }
}
