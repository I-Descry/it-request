<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('request')->nullable()->after('request_type');
        });

        Schema::table('archive_tickets', function (Blueprint $table) {
            $table->string('request')->nullable()->after('request_type');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('request');
        });

        Schema::table('archive_tickets', function (Blueprint $table) {
            $table->dropColumn('request');
        });
    }
};
