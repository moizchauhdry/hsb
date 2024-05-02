<?php

use App\Models\WorkOrder;

function calculation($wo_id, $category_id = NULL)
{
    $wo = WorkOrder::find($wo_id);
    $subtotal = $wo->items->where('wo_item_category_id', $category_id)->sum('wo_item_line_total');
    $discount = 0;
    $tax_rate = 5;
    $tax_amount = ($subtotal -  $discount) * $tax_rate / 100;
    $total = ($subtotal -  $discount) + $tax_amount;

    return [
        'subtotal' => format_number($subtotal),
        'discount' => format_number($discount),
        'tax_rate' => $tax_rate,
        'tax_amount' => format_number($tax_amount),
        'total' => format_number($total),
    ];
}

function format_number($number)
{
    return number_format((float)$number, 2, '.', '');
}
