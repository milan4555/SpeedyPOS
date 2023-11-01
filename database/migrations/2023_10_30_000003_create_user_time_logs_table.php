<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_time_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('employeeId');
            $table->date('startTime');
            $table->date('endTime');
            $table->integer('hoursWorked');
            $table->timestamps();

            $table->foreign('employeeId')
                ->references('employeeId')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_time_logs');
    }
};
