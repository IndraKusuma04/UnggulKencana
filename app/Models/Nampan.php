<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nampan extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table    = 'nampan';
    protected $fillable =
    [
        'nampan',
        'jenisproduk_id',
        'status'
    ];

    /**
     * Get the jenisProduk that owns the Nampan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenisproduk_id', 'id');
    }

    public function produk()
    {
        return $this->hasMany(NampanProduk::class, 'nampan_id');
    }
}
