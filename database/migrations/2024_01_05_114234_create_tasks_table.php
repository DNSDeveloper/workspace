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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('task');
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('service_id')->nullable();
            $table->boolean('is_priority')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->string('attach_done')->nullable();
            $table->string('report_done')->nullable();
            $table->timestamp('completed_time')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};