<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';

    protected $fillable = [
        'id_outlet',
        'kode_invoice',
        'id_member',
        'tgl',
        'batas_waktu',
        'tgl_bayar',
        'biaya_tambahan',
        'diskon',
        'pajak',
        'status',
        'dibayar',
        'id_user',
    ];

    protected function casts(): array
    {
        return [
            'tgl' => 'datetime',
            'batas_waktu' => 'datetime',
            'tgl_bayar' => 'datetime',
            'biaya_tambahan' => 'integer',
            'diskon' => 'float',
            'pajak' => 'integer',
        ];
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }

    // Helper: Hitung Subtotal dari rincian paket
    public function getSubtotalAttribute()
    {
        return $this->detailTransaksi->sum(function ($detail) {
            return ($detail->paket ? $detail->paket->harga : 0) * $detail->qty;
        });
    }

    // Helper: Hitung Total Akhir (Subtotal - Diskon + Pajak + Biaya Tambahan)
    public function getTotalBayarAttribute()
    {
        $subtotal = $this->subtotal;
        $nilaiDiskon = ($this->diskon / 100) * $subtotal;
        $total = ($subtotal - $nilaiDiskon) + $this->pajak + $this->biaya_tambahan;
        return max(0, round($total));
    }
}
