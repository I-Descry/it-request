<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_number_counter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('next_number')->default(1);
            $table->timestamps();
        });

        // Seed the counter with the next available number based on existing tickets
        $maxId = DB::table('tickets')->max('id');
        $nextNumber = $maxId ? $maxId + 1 : 1;

        DB::table('ticket_number_counter')->insert([
            'next_number' => $nextNumber,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_number_counter');
    }
};
