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
        Schema::create('products', function (Blueprint $table) {
            $table->id('productId');
            $table->string('productName');
            $table->string('productShortName');
            $table->integer('bPrice');
            $table->integer('nPrice');
            $table->integer('stock');
            $table->unsignedBigInteger('categoryId');
            $table->unsignedBigInteger('companyId');
            $table->timestamps();

            $table->foreign('categoryId')
                ->references('categoryId')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('companyId')
                ->references('companyId')
                ->on('companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
