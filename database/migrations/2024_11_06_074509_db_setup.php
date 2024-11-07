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
        Schema::create('traffic', function(Blueprint $table){
            $table->id();
            $table->integer('id_jenis');
            $table->integer('id_ruas');
            $table->integer('kecepatan');
            $table->dateTime('tanggal');
            $table->timestamps();
        });
        Schema::create('jenis_kendaraan', function(Blueprint $table){
            $table->id();
            $table->string('jenis');
            $table->timestamps();
        });
        Schema::create('ruas_jalan', function(Blueprint $table){
            $table->id();
            $table->string('ruas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
