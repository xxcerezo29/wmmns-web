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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('resident_id')->references('id')->on('residents')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->references('id')->on('collection_schedules')->onDelete('cascade');
            $table->enum('report_type', ['missed_collection', 'illegal_dumping'])->default('missed_collection');
            $table->string('location')->nullable();
            $table->string('barangay');
            $table->text('description'); 
            $table->enum('status', ['closed','pending', 'reviewed', 'resolved'])->default('pending');
            $table->json('photo_url')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
