<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatDatesTrait
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->dates) && !is_null($value)) {
            return Carbon::parse($value)->format('d-m-Y');
        }

        return $value;
    }
}
