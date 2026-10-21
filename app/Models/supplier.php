<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasUuids;

    protected $table = 'suppliers';
    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'pic_name',
        'pic_contact_number',
        'pic_contact_email'
    ];
}
