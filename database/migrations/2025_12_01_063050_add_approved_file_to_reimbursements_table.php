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
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->string('approved_file')->nullable()->after('file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropColumn('approved_file');
        });
    }
};
