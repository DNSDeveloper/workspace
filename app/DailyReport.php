<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report',
        'employee_id',
        'task'
    ];

    public function employee(){
        return $this->belongsTo('App\Employee');
    }
    public function task(){
        return $this->belongsTo('App\Task');
    }
}