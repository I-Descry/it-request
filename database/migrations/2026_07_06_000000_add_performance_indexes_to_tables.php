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
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('status');
            $table->index('request_type');
            $table->index('requested_by');
            $table->index('assisted_by');
            $table->index('department');
            // We already have index on id, ticket_no. deleted_at gets an index typically but let's add it.
            $table->index('deleted_at');
            
            // Composite index for common dashboard query
            $table->index(['deleted_at', 'created_at', 'request_type'], 'tickets_dashboard_composite_index');
        });

        Schema::table('archive_tickets', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('request_type');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('last_name');
            $table->index('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
            $table->dropIndex(['request_type']);
            $table->dropIndex(['requested_by']);
            $table->dropIndex(['assisted_by']);
            $table->dropIndex(['department']);
            $table->dropIndex(['deleted_at']);
            $table->dropIndex('tickets_dashboard_composite_index');
        });

        Schema::table('archive_tickets', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['request_type']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['last_name']);
            $table->dropIndex(['department']);
        });
    }
};
