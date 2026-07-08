<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('lead_tasks', 'type')) {
                $table->string('type', 20)->default('todo')->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('lead_tasks', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
