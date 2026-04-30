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
        Schema::create('agent_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('role'); // agen / pemilik

            $table->string('nama');
            $table->string('nama_agen')->nullable(); // khusus agen
            $table->string('nama_pemilik_agen')->nullable(); // khusus agen

            $table->string('no_hp');
            $table->string('email');
            $table->text('alamat');

            $table->string('status')->default('pending'); // pending / approved / rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_requests');
    }
};
