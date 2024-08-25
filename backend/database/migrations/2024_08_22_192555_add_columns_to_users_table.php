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
        Schema::table('users', function (Blueprint $table) {
            $table->date('subscription_end')->nullable()->after('email_verified_at');
            $table->string('avatar')->nullable();
            $table->string('lang')->default('en');
            $table->boolean('on_validation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subscription_end');
            $table->dropColumn('avatar');
            $table->dropColumn('lang');
            $table->dropColumn('on_validation');
        });
    }
};
