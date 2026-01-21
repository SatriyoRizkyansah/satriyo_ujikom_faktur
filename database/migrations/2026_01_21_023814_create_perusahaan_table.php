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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->bigIncrements('id_perusahaan');
            $table->string('nama_perusahaan');
            $table->string('alamat');
            $table->string('no_telp', 30)->nullable();
            $table->string('fax', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
