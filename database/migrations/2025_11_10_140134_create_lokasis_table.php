<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasis', function (Blueprint $table) {

            $table->id(); 

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->string('nama_lokasi', 255);
            $table->string('lat_kantor', 255)->nullable();
            $table->string('long_kantor', 255)->nullable();
            $table->integer('radius')->default(100);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('pegawais')
                ->nullOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('pegawais')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lokasis');
        Schema::enableForeignKeyConstraints();
    }
};
