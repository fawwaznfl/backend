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
        Schema::table('shift_mapping', function (Blueprint $table) {
            $table->integer('toleransi_telat')
                ->default(0)
                ->after('shift_id');

            $table->string('status_toleransi')
                ->nullable()
                ->after('toleransi_telat');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_mapping', function (Blueprint $table) {
            //
        });
    }
};
