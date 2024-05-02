<?php

namespace Database\Seeders;

use App\Models\CustomerAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerAccount::create([
            'name' => 'Moiz Chauhdry',
            'phone_home' => '090078601',
            'phone_mobile' => '03204650584',
            'email' => 'moizchauhdry',
            'address' => 'Arfa Software Technology Park, Lahore',
            'city' => 'Lahore',
            'province' => 'Punjab',
            'postal_code' => '54000',
            'payment_method' => 'CC',
            'products' => json_encode(
                [
                    [
                        'name' => 'Yamaha 125',
                        'qty' => 1,
                        'desc' => 'YBR - 2023'
                    ],
                    [
                        'name' => 'Honda 70',
                        'qty' => 2,
                        'desc' => 'CD - 2005'
                    ]
                ]
            ),
            'services_purchased' => json_encode([
                [
                    'name' => 'Technical Support',
                ]
            ]),
            'product_warranty' => '1 Year',
            'comments' => 'Hello World',
        ]);
    }
}
