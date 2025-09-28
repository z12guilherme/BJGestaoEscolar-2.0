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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year')->nullable();
            $table->unsignedBigInteger('professor_id')->nullable();
            $table->unsignedBigInteger('escola_id')->nullable();
            $table->timestamps();

            $table->foreign('professor_id')->references('id')->on('professores')->onDelete('set null');
            $table->foreign('escola_id')->references('id')->on('escolas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
