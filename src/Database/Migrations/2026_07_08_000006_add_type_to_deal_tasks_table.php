<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('deal_tasks', 'type')) {
                $table->string('type', 20)->default('todo')->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deal_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('deal_tasks', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
