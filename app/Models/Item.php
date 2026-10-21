<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;
    protected $table = 'items';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'jumlah',
    ];
}
