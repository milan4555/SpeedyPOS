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
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->timestamp('startTime');
            $table->jsonb('breakTime')->nullable();
            $table->timestamp('endTime')->nullable();
            $table->time('hoursWorked')->nullable();
            $table->time('breakSum')->nullable();
            $table->time('totalWorkedHours')->nullable();
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
