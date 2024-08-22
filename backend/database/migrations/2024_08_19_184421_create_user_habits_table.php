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
        Schema::create('user_habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('type');
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('user_unit_id')->nullable()->constrained();
            $table->foreignId('default_unit_id')->nullable()->constrained();
            $table->integer('goal')->default(1);
            $table->integer('reward');
            $table->integer('week_days_bitmask');
            $table->boolean('archived')->default(false);
            $table->timestamp('due_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_habits');
    }
};
