<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            if (!Schema::hasColumn('deals', 'lost_reason_id')) {
                $table->foreignId('lost_reason_id')->nullable()->after('status')
                    ->constrained('lost_reasons')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            if (Schema::hasColumn('deals', 'lost_reason_id')) {
                $table->dropConstrainedForeignId('lost_reason_id');
            }
        });
    }
};
