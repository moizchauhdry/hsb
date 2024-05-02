<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Product 1',
            'Product 2',
        ];

        foreach ($products as $key => $product) {
            Product::create([
                'name' => $product
            ]);
        }
    }
}
