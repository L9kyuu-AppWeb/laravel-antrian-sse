<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $prefix
 * @property int $digit_number
 * @property int $start_number
 * @property bool $reset_number
 * @property bool $is_active
 * @property int $service_category_id
 * @property ServiceCategory $serviceCategory
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'prefix', 'digit_number', 'start_number', 'reset_number', 'is_active', 'service_category_id'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<ServiceCategory, $this>
     */
    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    /**
     * @return HasMany<Counter, $this>
     */
    public function counters(): HasMany
    {
        return $this->hasMany(Counter::class);
    }

    protected function casts(): array
    {
        return [
            'reset_number' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
