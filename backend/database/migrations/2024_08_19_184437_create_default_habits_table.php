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
        Schema::create('default_habits', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('default_unit_id');
            $table->integer('goal')->default(1);
            $table->integer('reward');
            $table->integer('week_days_bitmask');
            $table->boolean('visible')->default(true);

            $table->foreign('default_unit_id')->references('id')->on('default_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_habits');
    }
};
