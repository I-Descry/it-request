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
        Schema::create('archive_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_ticket_id')->nullable();
            $table->string('ticket_no');
            $table->string('request_type');
            $table->text('request_details');
            $table->string('affected_system')->nullable();
            $table->string('requested_by');
            $table->string('position')->nullable();
            $table->string('branch')->nullable();
            $table->string('assisted_by')->nullable();
            $table->string('status')->default('Open');
            $table->text('remarks')->nullable();
            $table->string('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('original_created_at')->nullable();
            $table->timestamp('original_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('archive_ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_ticket_id')->constrained('archive_tickets')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_ticket_attachments');
        Schema::dropIfExists('archive_tickets');
    }
};
