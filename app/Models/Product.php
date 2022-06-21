<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'u_o_m_id',
        'code',
        'unique_name',
        'name',
        'size',
        'color',
        'selling_price',
        'description',
    ];

    protected $casts = [
        'stocks' => 'integer',
        'selling_price' => 'float',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UOM::class, 'u_o_m_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Product $model) {
            $model->unique_name = trim_whitespaces("$model->category->name $model->brand->name $model->name $model->color $model->size $model->uom->short_name");
        });
    }
}
