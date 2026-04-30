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
        Schema::create('upgrade_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('nama');
            $table->string('email');
            $table->string('no_hp');
            $table->text('alamat');

            $table->string('role'); // agen / pemilik

            // khusus agen
            $table->string('nama_agen')->nullable();
            $table->string('nama_pemilik_agen')->nullable();

            $table->string('status')->default('pending'); // 🔥 langsung tambahkan di sini

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_requests');
    }
};
