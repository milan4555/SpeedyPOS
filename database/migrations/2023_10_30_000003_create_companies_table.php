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
        Schema::create('companies', function (Blueprint $table) {
            $table->id('companyId');
            $table->string('companyName');
            $table->integer('postcode');
            $table->string('city');
            $table->string('street');
            $table->integer('streetNumber');
            $table->boolean('isSupplier');
            $table->string('taxNumber');
            $table->string('owner')->nullable();
            $table->bigInteger('phoneNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
