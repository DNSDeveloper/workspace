<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tanggal_reimbursement',
        'minggu',
        'jenis',
        'deskripsi',
        'nominal',
        'tanggal_transfer', 
        'file_employee',
        'file_admin',
        'status',
        'catetan'
    ];

    public function employee() {
        return $this->belongsTo('App\Employee');
    }
}