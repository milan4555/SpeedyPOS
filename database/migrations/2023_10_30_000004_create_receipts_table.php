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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id('receiptId');
            $table->boolean('isInvoice');
            $table->date('date');
            $table->integer('sumPrice');
            $table->string('paymentType');
            $table->unsignedBigInteger('employeeId');
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
        Schema::dropIfExists('receipts');
    }
};
