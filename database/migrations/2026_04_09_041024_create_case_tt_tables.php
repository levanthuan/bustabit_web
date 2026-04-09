<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (['case_3', 'case_5', 'case_7', 'case_10'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->integer('id');
                $table->primary('id');
                $table->integer('count')->nullable();
                $table->integer('busted');
                $table->tinyInteger('dead_flg')->nullable();
                $table->dateTime('game_datetime')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['case_10', 'case_7', 'case_5', 'case_3'] as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
