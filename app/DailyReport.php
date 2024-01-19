<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report',
        'ask',
        'employee_id',
        'task_id'
    ];

    public function employee(){
        return $this->belongsTo('App\Employee');
    }
}