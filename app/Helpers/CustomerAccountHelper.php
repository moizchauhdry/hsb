<?php

use App\Models\Product;
use App\Models\Service;

function product_options()
{
    $products = Product::orderBy('name', 'asc')->get();

    $product_options = [];
    foreach ($products as $key => $item) {
        $product_options[] = [
            'value' => $item->id,
            'label' => $item->name,
        ];
    }

    return $product_options;
}

function service_options()
{
    $services = Service::orderBy('name', 'asc')->get();

    $service_options = [];
    foreach ($services as $key => $item) {
        $service_options[] = [
            'value' => $item->id,
            'label' => $item->name,
        ];
    }

    return $service_options;
}


function product_name($id)
{
    return Product::find($id)->name;
}

function service_name($id)
{
    return Service::find($id)->name;
}
