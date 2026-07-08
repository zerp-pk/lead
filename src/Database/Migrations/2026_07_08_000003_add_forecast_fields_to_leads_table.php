<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('subject');
            }
            if (!Schema::hasColumn('leads', 'expected_close_date')) {
                $table->date('expected_close_date')->nullable()->after('date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('leads', 'expected_close_date')) {
                $table->dropColumn('expected_close_date');
            }
        });
    }
};
