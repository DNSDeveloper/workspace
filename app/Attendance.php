<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $fillable = [
        'employee_id', 
        'entry_ip', 
        'entry_time', 
        'entry_location', 
        'time',
        'no_kursi',
        'registered',
        'img_present',
        'jam_masuk',
        'jam_pulang',
        'status',
        'created_at'
    ];
    public function employee() {
        return $this->belongsTo('App\Employee');
    }
}