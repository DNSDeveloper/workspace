<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'task',
        'deadline',
        'note',
        'status',
        'category',
        'is_priority',
        'unit_id',
        'service_id',
        'completed_time',
        'attach_done',
        'report_done'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }
    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }
    public function service()
    {
        return $this->belongsTo('App\Service');
    }
    public function subtasks()
    {
        return $this->hasMany('App\Subtask');
    }
}