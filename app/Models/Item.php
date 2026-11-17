<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'items';
    protected $fillable = [
        'code',
        'product_name',
        'product_category',
        'quantity',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q, string $keyword) {
            $q->where(function (Builder $q) use ($keyword) {
                $q->where('code', 'like', "%{$keyword}%")
                    ->orWhere('product_name', 'like', "%{$keyword}%")
                    ->orWhere('product_category', 'like', "%{$keyword}%");
            });
        });
    }
}
