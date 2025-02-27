<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() 
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->foreign('manager_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() 
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
    }
};
