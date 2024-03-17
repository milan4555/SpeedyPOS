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
        Schema::create('storage_places', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('productId');
            $table->integer('index');
            $table->integer('howMany');
            $table->string('storagePlace');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_places');
    }
};
