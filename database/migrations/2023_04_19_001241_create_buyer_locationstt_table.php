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
        // Schema::create('buyer_locations', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('state');
        //     $table->string('local_government')->nullable();
        //     $table->string('street');
        //     $table->string('buyer_id');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_locations');
    }
};
