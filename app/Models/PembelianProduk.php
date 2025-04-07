<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianProduk extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table = 'pembelian_produk';
    protected $fillable = [
        'kodepembelianproduk',
        'kodeproduk',
        'nama',
        'jenisproduk_id',
        'harga',
        'harga_beli',
        'berat',
        'karat',
        'lingkar',
        'panjang',
        'keterangan',
        'kondisi_id',
        'image_produk',
        'total',
        'oleh',
        'status'
    ];

    /**
     * Get the user that owns the PembelianProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'oleh', 'id');
    }

    /**
     * Get the kondisi that owns the PembelianProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kondisi(): BelongsTo
    {
        return $this->belongsTo(Kondisi::class, 'kondisi_id', 'id');
    }

    /**
     * Get the jenisproduk that owns the PembelianProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenisproduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenisproduk_id', 'id');
    }

    /**
     * Get the produk that owns the PembelianProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
