<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->boolean('cenro')->default(false);
        });
        Schema::table('drivers', function (Blueprint $table) {
            $table->boolean('cenro')->default(false);
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->boolean('cenro')->default(false);
        });
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->boolean('cenro')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
