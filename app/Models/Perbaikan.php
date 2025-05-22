<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perbaikan extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table    = 'perbaikan';
    protected $fillable =
    [
        'kodeperbaikan',
        'produk_id',
        'kondisi_id',
        'keterangan',
        'tanggalmasuk',
        'tanggalkeluar',
        'status',
        'oleh',
    ];

    /**
     * Get the produk that owns the Perbaikan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    /**
     * Get the kondisi that owns the Perbaikan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kondisi(): BelongsTo
    {
        return $this->belongsTo(Kondisi::class, 'kondisi_id', 'id');
    }
}
