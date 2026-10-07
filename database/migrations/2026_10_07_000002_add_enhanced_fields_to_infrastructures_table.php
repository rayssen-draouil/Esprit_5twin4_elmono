<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->unique()->after('id');
            $table->string('location')->nullable()->after('name');
            $table->decimal('latitude', 10, 7)->nullable()->after('location');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('capacity')->nullable()->after('type');
            $table->string('condition')->default('good')->after('status'); // excellent, good, fair, poor, critical
            $table->string('criticality')->default('medium')->after('condition'); // low, medium, high, vital
            $table->date('commissioning_date')->nullable()->after('installation_date');
            $table->date('next_maintenance_date')->nullable()->after('last_maintenance_date');
            $table->string('image_path')->nullable()->after('next_maintenance_date');

            $table->index('status');
            $table->index('type');
            $table->index('condition');
            $table->index('criticality');
        });
    }

    public function down(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['condition']);
            $table->dropIndex(['criticality']);

            $table->dropColumn([
                'reference_code',
                'location',
                'latitude',
                'longitude',
                'capacity',
                'condition',
                'criticality',
                'commissioning_date',
                'next_maintenance_date',
                'image_path',
            ]);
        });
    }
};
