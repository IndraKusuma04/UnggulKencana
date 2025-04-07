<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table = 'pembelian';
    protected $fillable = [
        'kodepembelian',
        'kodepembelianproduk',
        'suplier_id',
        'pelanggan_id',
        'nonsuplierdanpembeli',
        'tanggal',
        'total_harga',
        'diskon',
        'grand_total',
        'oleh',
        'catatan',
        'status',
    ];

    /**
     * Get the suplier that owns the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }

    /**
     * Get the pelanggan that owns the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(pelanggan::class, 'pelanggan_id', 'id');
    }

    /**
     * Get all of the pembelianproduk for the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pembelianproduk(): HasMany
    {
        return $this->hasMany(PembelianProduk::class, 'kodepembelianproduk', 'kodepembelianproduk');
    }
}
