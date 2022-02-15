<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'invoice',
        'or_number',
        'payment_method',
        'amount',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            $model->sold_by = auth()->id();
            $model->sold_at = now();
        });

        static::deleting(function ($model) {
            $model->transactions()->delete();
        });
    }

        public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
