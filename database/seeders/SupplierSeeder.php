<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT. Supplier Elektronik Maju',
                'company_name' => 'PT. Supplier Elektronik Maju',
                'email' => 'elektronik@supplier.com',
                'phone' => '02112345678',
                'address' => 'Jl. Industri Elektronik No. 123, Jakarta Barat',
                'city' => 'Jakarta',
                'tax_number' => 'NPWP-123456789012345',
            ],
            [
                'name' => 'CV. Pakaian Berkualitas',
                'company_name' => 'CV. Pakaian Berkualitas',
                'email' => 'pakaian@supplier.com',
                'phone' => '02123456789',
                'address' => 'Jl. Tekstil No. 456, Bandung',
                'city' => 'Bandung',
                'tax_number' => 'NPWP-234567890123456',
            ],
            [
                'name' => 'PT. Makanan Sehat Indonesia',
                'company_name' => 'PT. Makanan Sehat Indonesia',
                'email' => 'makanan@supplier.com',
                'phone' => '02134567890',
                'address' => 'Jl. Food Industry No. 789, Surabaya',
                'city' => 'Surabaya',
                'tax_number' => 'NPWP-345678901234567',
            ],
            [
                'name' => 'UD. Perabotan Jaya',
                'company_name' => 'UD. Perabotan Jaya',
                'email' => 'perabotan@supplier.com',
                'phone' => '02145678901',
                'address' => 'Jl. Mebel No. 321, Jepara',
                'city' => 'Jepara',
                'tax_number' => 'NPWP-456789012345678',
            ],
            [
                'name' => 'PT. Olahraga Prima',
                'company_name' => 'PT. Olahraga Prima',
                'email' => 'olahraga@supplier.com',
                'phone' => '02156789012',
                'address' => 'Jl. Sport Center No. 654, Jakarta Selatan',
                'city' => 'Jakarta',
                'tax_number' => 'NPWP-567890123456789',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Beberapa supplier nonaktif
        Supplier::factory(2)->inactive()->create();
    }
}
