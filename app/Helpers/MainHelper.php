<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function format_number($number)
{
    $formatted_number = number_format((float)$number, 2, '.', ',');
    return $formatted_number;
}

function getRoleID($user)
{    
    $role_id = NULL;
    if ($user && isset($user->roles[0])) {
        $role_id = $user->roles[0]->id;
    }

    return $role_id;
}

function dateFormat($date)
{
    return Carbon::parse($date)->format('d-m-Y');
}

function getMonthName($month_number)
{
    $date = Carbon::createFromDate(null, $month_number, 1);
    return $date->format('F');
}