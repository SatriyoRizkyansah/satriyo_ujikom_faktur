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
        Schema::create('detail_faktur', function (Blueprint $table) {
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('no_faktur');
            $table->integer('qty');
            $table->decimal('price', 15, 2);

            $table->primary(['id_produk', 'no_faktur']);

            $table->foreign('id_produk')->references('id_produk')->on('produk')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('no_faktur')->references('no_faktur')->on('faktur')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_faktur');
    }
};
