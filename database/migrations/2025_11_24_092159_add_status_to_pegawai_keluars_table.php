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
        Schema::table('pegawai_keluars', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('upload_file');
            $table->text('note_approver')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pegawai_keluars', function (Blueprint $table) {
            $table->dropColumn(['status', 'note_approver']);
        });
    }
};
