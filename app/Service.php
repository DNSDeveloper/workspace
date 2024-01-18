<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'name'
    ];

    public function unit() {
        return $this->belongsTo('App\Unit');
    }
}