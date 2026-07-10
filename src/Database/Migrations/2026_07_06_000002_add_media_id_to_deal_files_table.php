<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_files', function (Blueprint $table) {
            if (!Schema::hasColumn('deal_files', 'media_id')) {
                $table->foreignId('media_id')->nullable()->after('file_path')
                    ->constrained('media')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('deal_files', function (Blueprint $table) {
            if (Schema::hasColumn('deal_files', 'media_id')) {
                $table->dropConstrainedForeignId('media_id');
            }
        });
    }
};
