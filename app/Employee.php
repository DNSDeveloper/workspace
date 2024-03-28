<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $dates = ['created_at', 'dob','updated_at', 'join_date'];
    protected $fillable = ['user_id', 'first_name', 'last_name', 'sex', 'dob', 'join_date', 'position_id', 'department_id', 'salary', 'photo','phone','hak_cuti'];
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function tasks() {
        return $this->hasMany('App\Task');
    }

    public function position() {
        return $this->belongsTo('App\Position');
    }

    public function department() {
        // return $this->hasOne('App\Department');
        return $this->belongsTo('App\Department');
    }

    public function attendance() {
        return $this->hasMany('App\Attendance');
    }

    public function leave() {
        return $this->hasMany('App\Leave');
    }

    public function expense() {
        return $this->hasMany('App\Expense');
    }
}