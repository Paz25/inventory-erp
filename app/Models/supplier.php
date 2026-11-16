<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Supplier extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'suppliers';
    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'pic_name',
        'pic_contact_number',
        'pic_contact_email'
    ];

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q, string $keyword) {
            $q->where(function (Builder $q) use ($keyword) {
                $q->where('supplier_name', 'like', "%{$keyword}%")
                    ->orWhere('pic_name', 'like', "%{$keyword}%");
            });
        });
    }
}
