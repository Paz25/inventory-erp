<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'code' => 'IDM001',
                'product_name' => 'Indomie Goreng',
                'product_category' => 'Instant Food',
                'quantity' => 10
            ],
            [
                'code' => 'AQUA071',
                'product_name' => 'Aqua 600ml',
                'product_category' => 'Beverage',
                'quantity' => 500
            ],
            [
                'code' => 'NCF200',
                'product_name' => 'Nescafe instant 200gr',
                'product_category' => 'Breakfast',
                'quantity' => 20
            ],
            [
                'code' => 'GRD505',
                'product_name' => 'DAIA Sabun Cuci Baju',
                'product_category' => 'FMCG',
                'quantity' => 25
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
