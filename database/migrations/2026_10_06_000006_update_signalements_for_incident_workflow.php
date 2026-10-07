<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            if (! Schema::hasColumn('signalements', 'type')) {
                $table->string('type')->default('Autre')->after('reporter_email');
            }
            if (! Schema::hasColumn('signalements', 'location')) {
                $table->string('location')->nullable()->after('description');
            }
            if (! Schema::hasColumn('signalements', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('location');
            }
            if (! Schema::hasColumn('signalements', 'priority')) {
                $table->string('priority')->nullable()->after('photo_path');
            }
            $table->foreignId('incident_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            $table->dropColumn(['type', 'location', 'photo_path', 'priority']);
        });
    }
};
