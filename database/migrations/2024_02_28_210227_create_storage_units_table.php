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
        Schema::create('storage_units', function (Blueprint $table) {
            $table->id('storageId');
            $table->string('storageName');
            $table->integer('numberOfRows');
            $table->integer('widthNumber');
            $table->integer('heightNumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_units');
    }
};
