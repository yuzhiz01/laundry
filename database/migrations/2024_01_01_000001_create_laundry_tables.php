<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. tb_outlet
        Schema::create('tb_outlet', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('tlp', 15);
            $table->timestamps();
        });

        // 2. tb_user
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('username', 30)->unique();
            $table->string('password');
            $table->foreignId('id_outlet')->nullable()->constrained('tb_outlet')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('role', ['admin', 'kasir', 'owner'])->default('kasir');
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. tb_paket
        Schema::create('tb_paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_outlet')->constrained('tb_outlet')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('jenis', ['kiloan', 'selimut', 'bed_cover', 'kaos', 'lain']);
            $table->string('nama_paket', 100);
            $table->integer('harga');
            $table->timestamps();
        });

        // 4. tb_member
        Schema::create('tb_member', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tlp', 15);
            $table->timestamps();
        });

        // 5. tb_transaksi
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_outlet')->constrained('tb_outlet')->cascadeOnUpdate();
            $table->string('kode_invoice', 100)->unique();
            $table->foreignId('id_member')->constrained('tb_member')->cascadeOnUpdate();
            $table->dateTime('tgl');
            $table->dateTime('batas_waktu');
            $table->dateTime('tgl_bayar')->nullable();
            $table->integer('biaya_tambahan')->default(0);
            $table->double('diskon')->default(0);
            $table->integer('pajak')->default(0);
            $table->enum('status', ['baru', 'proses', 'selesai', 'diambil'])->default('baru');
            $table->enum('dibayar', ['dibayar', 'belum_dibayar'])->default('belum_dibayar');
            $table->foreignId('id_user')->constrained('tb_user')->cascadeOnUpdate();
            $table->timestamps();
        });

        // 6. tb_detail_transaksi
        Schema::create('tb_detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('tb_transaksi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_paket')->constrained('tb_paket')->cascadeOnUpdate();
            $table->double('qty');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_transaksi');
        Schema::dropIfExists('tb_transaksi');
        Schema::dropIfExists('tb_member');
        Schema::dropIfExists('tb_paket');
        Schema::dropIfExists('tb_user');
        Schema::dropIfExists('tb_outlet');
    }
};
