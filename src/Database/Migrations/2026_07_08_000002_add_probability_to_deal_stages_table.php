<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_stages', function (Blueprint $table) {
            if (!Schema::hasColumn('deal_stages', 'probability')) {
                $table->unsignedTinyInteger('probability')->default(10)->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deal_stages', function (Blueprint $table) {
            if (Schema::hasColumn('deal_stages', 'probability')) {
                $table->dropColumn('probability');
            }
        });
    }
};
