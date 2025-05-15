<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_pembayarans')->onDelete('cascade');
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->decimal('total', 10, 2);
            $table->date('tanggal_buat');
            $table->date('tanggal_tempo')->nullable();
            $table->string('foto')->nullable();  // Menyimpan foto pembayaran
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};
