<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatDatesTrait
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // Check if the key is in the dates array and is not null
        if ($this->isDateAttribute($key) && !is_null($value)) {
            try {
                return Carbon::parse($value)->format('d-m-Y');
            } catch (\Exception $e) {
                // Optionally, handle invalid date formats gracefully
                return $value;
            }
        }

        return $value;
    }

    protected function isDateAttribute($key)
    {
        return in_array($key, $this->dates);
    }
}
