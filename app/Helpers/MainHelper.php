<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
    if ($date) {
        return Carbon::parse($date)->format('d-m-Y');
    } else {
        return NULL;
    }
}

function excelDateFormat($date)
{
    if ($date) {
        return Date::excelToDateTimeObject($date)->format('Y-m-d');
    } else {
        return NULL;
    }
}

function getMonthName($month_number)
{
    $date = Carbon::createFromDate(null, $month_number, 1);
    return $date->format('F');
}

function getClientName($id)
{
    $client = User::find($id);
    if ($client) {
        return $client->name;
    } else {
        return NULL;
    }
}