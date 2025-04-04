<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NampanProduk extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table    = 'nampan_produk';
    protected $fillable =
    [
        'nampan_id',
        'produk_id',
        'tanggal',
        'oleh',
        'status'
    ];

    /**
     * Get the nampan that owns the NampanProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nampan(): BelongsTo
    {
        return $this->belongsTo(Nampan::class, 'nampan_id', 'id');
    }

    /**
     * Get the produk that owns the NampanProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    /**
     * Get the user that owns the NampanProduk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'oleh', 'id');
    }
}
