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
        Schema::table('kontraks', function (Blueprint $table) {
            $table->boolean('notified_h30')->default(false)->after('file_kontrak');
            $table->boolean('notified_h7')->default(false)->after('notified_h30');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('kontraks', function (Blueprint $table) {
            $table->dropColumn(['notified_h30', 'notified_h7']);
        });
    }
};
