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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('schedule_slot_id')->index()->nullable();
            $table->foreign('schedule_slot_id')->references('id')->on('schedule_slots')->onDelete('cascade');
            // $table->string('meeting_provider')->index()->nullable();
            // $table->text('meeting_url')->nullable();
            $table->enum('meeting_status', [
                'pending',
                'awaiting-payment',
                'confirmed',
                'started',
                'in-progress',
                'canceled',
                'timeout',
                'finished'
            ])->default('pending')->index();
            // later i need to add ref here for the transaction     
            $table->date('meeting_date')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
