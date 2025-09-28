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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->enum('role', ['root', 'diretor', 'professor', 'tutor', 'aluno', 'responsavel']);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('escola_id')->nullable();
            $table->timestamps();

            $table->foreign('escola_id')->references('id')->on('escolas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
