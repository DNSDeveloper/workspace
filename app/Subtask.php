<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'employee_id',
        'description',
        'file',
        'deadline',
        'status',
        'completed_time',
        'attach_done',
        'report_done'
    ];

    public function task() {
        return $this->belongsTo('App\Task');
    }
    public function employee() {
        return $this->belongsTo('App\Employee');
    }
}