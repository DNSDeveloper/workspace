<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'name'
    ];

    public function services() {
        return $this->hasMany('App\Services');
    }

    public function tasks() {
        return $this->hasMany('App\Task');
    }
}