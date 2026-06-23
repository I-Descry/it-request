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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('ticket_no')->unique();
        $table->string('request_type');
        $table->text('request_details');
        $table->string('affected_system')->nullable();
        $table->string('requested_by');
        $table->string('assisted_by')->nullable();
        $table->string('status')->default('Open');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}
};
