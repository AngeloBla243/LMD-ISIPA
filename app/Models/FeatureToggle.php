<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    protected $fillable = ['feature_name', 'enabled', 'start_date', 'end_date'];

    public function isActive(): bool
    {
        $today = now()->toDateString();

        return $this->enabled
            && (!$this->start_date || $today >= $this->start_date)
            && (!$this->end_date || $today <= $this->end_date);
    }

    public static function isFeatureActive(string $featureName): bool
    {
        $feature = self::where('feature_name', $featureName)->first();

        return $feature ? $feature->isActive() : false;
    }
}
