<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'supplier_name' => 'PT. Bumi Sejahtera Utama',
                'supplier_address' => 'Jl. Garuda No.25 Jakarta',
                'pic_name' => 'Pasha Rakha',
                'pic_contact_number' => '08123456789',
                'pic_contact_email' => 'pasha@bsu.com'
            ],
            [
                'supplier_name' => 'CV. Dewanta Sejahtera',
                'supplier_address' => 'Jl. Pancasila No.39 Bogor',
                'pic_name' => 'Putu Anjes',
                'pic_contact_number' => '08987654321',
                'pic_contact_email' => 'anjes@dewanta.com'
            ],
            [
                'supplier_name' => 'PT. Hompimpa Alayyum Gambreng',
                'supplier_address' => 'Jl. Jend. Sudirman No.123',
                'pic_name' => 'Szwagery',
                'pic_contact_number' => '0876543219',
                'pic_contact_email' => 'gery.s@hompimpa.com'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
