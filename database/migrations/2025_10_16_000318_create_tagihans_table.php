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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tagihan'); // contoh: "SPP Oktober 2025"
            $table->decimal('jumlah', 15, 2); // nominal tagihan
            $table->date('tanggal_jatuh_tempo')->nullable(); // opsional, bisa kosong
            $table->string('status')->default('aktif'); // aktif / lunas / menunggak
            $table->unsignedBigInteger('siswa_id'); // relasi ke tabel siswa atau users

            // jika siswa ada di tabel users
            $table->foreign('siswa_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
