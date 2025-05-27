<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokNampan extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table    = 'stok_nampan';
    protected $fillable =
    [
        'nampan_id',
        'tanggal',
        'stokprodukawal',
        'stokprodukakhir',
        'stokawalberat',
        'stokakhirberat',
        'status'
    ];

    /**
     * Get the nampan that owns the StokNampan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nampan(): BelongsTo
    {
        return $this->belongsTo(Nampan::class, 'nampan_id', 'id');
    }
}
