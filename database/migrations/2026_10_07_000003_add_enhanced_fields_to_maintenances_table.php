<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->unique()->after('id');
            $table->foreignId('technician_id')->nullable()->after('infrastructure_id')->constrained('techniciens')->nullOnDelete();
            $table->string('team')->nullable()->after('technician_id');
            $table->string('priority')->default('medium')->after('status'); // low, medium, high, critical
            $table->decimal('cost', 10, 2)->nullable()->after('priority');
            $table->decimal('duration_hours', 5, 2)->nullable()->after('cost');
            $table->text('result')->nullable()->after('description');
            $table->timestamp('started_at')->nullable()->after('scheduled_at');
            $table->timestamp('completed_at')->nullable()->after('performed_at');
            $table->date('next_maintenance_date')->nullable()->after('completed_at');

            $table->index('status');
            $table->index('type');
            $table->index('priority');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['scheduled_at']);

            $table->dropForeign(['technician_id']);

            $table->dropColumn([
                'reference_code',
                'technician_id',
                'team',
                'priority',
                'cost',
                'duration_hours',
                'result',
                'started_at',
                'completed_at',
                'next_maintenance_date',
            ]);
        });
    }
};
