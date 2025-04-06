<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table    = 'transaksi';
    protected $fillable =
    [
        'kodetransaksi',
        'kodekeranjang_id',
        'pelanggan_id',
        'diskon_id',
        'total',
        'tanggal',
        'oleh',
        'status'
    ];

    /**
     * Get the keranjang that owns the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function keranjang(): BelongsTo
    {
        return $this->belongsTo(Keranjang::class, 'kodekeranjang_id', 'kodekeranjang');
    }

    /**
     * Get the pelanggan that owns the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    /**
     * Get the diskon that owns the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diskon(): BelongsTo
    {
        return $this->belongsTo(Diskon::class, 'diskon_id', 'id');
    }

    /**
     * Get the user that owns the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'oleh', 'id');
    }
}
