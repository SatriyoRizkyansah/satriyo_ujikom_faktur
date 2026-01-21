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
        Schema::create('faktur', function (Blueprint $table) {
            $table->bigIncrements('no_faktur');
            $table->date('tgl_faktur');
            $table->date('due_date');
            $table->string('metode_bayar');
            $table->decimal('ppn', 5, 2)->default(0);
            $table->decimal('dp', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->string('user');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_perusahaan');

            $table->foreign('id_customer')->references('id_customer')->on('customer')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur');
    }
};
