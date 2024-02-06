<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->date('tanggal_reimbursement');
            $table->string('minggu');
            $table->string('jenis');
            $table->text('deskripsi');
            $table->bigInteger('nominal');
            $table->date('tanggal_transfer');
            $table->string('file_employee');
            $table->string('file_admin');
            $table->string('status');
            $table->string('catetan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reimbursements');
    }
};