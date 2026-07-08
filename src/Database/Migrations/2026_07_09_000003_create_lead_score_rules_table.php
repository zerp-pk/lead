<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lead_score_rules')) {
            return;
        }
        Schema::create('lead_score_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('field', 40);          // signal key, e.g. email_present, activity_count
            $table->string('operator', 20);       // is_set | equals | gte | lte
            $table->string('value')->nullable();  // comparison value (string; cast per operator)
            $table->integer('points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('creator_id')->nullable()->index();
            $table->foreignId('created_by')->nullable()->index();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_score_rules');
    }
};
