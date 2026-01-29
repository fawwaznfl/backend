<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->onDelete('cascade');

            $table->foreignId('kategori_reimbursement_id')
                ->constrained('kategori_reimbursements')
                ->onDelete('cascade');

            $table->date('tanggal');
            $table->string('event')->nullable();

            $table->enum('metode_reim', ['cash', 'transfer'])
                ->default('cash');

            $table->string('no_rekening')->nullable();

            $table->integer('jumlah')->nullable();
            $table->integer('terpakai')->default(1);
            $table->decimal('total', 15, 2)->nullable();
            $table->integer('sisa')->nullable();

            $table->enum('status', ['pending', 'approve', 'reject'])
                ->default('pending');

            $table->string('file')->nullable();
            $table->string('approved_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
