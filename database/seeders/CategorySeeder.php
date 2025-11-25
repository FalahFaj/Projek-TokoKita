<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['name' => 'Pakaian', 'description' => 'Pakaian pria, wanita, dan anak-anak'],
            ['name' => 'Makanan & Minuman', 'description' => 'Makanan ringan dan minuman'],
            ['name' => 'Perabotan', 'description' => 'Perabotan rumah tangga'],
            ['name' => 'Olahraga', 'description' => 'Alat olahraga dan fitness'],
            ['name' => 'Kesehatan', 'description' => 'Produk kesehatan dan kecantikan'],
            ['name' => 'Buku & Alat Tulis', 'description' => 'Buku, alat tulis, dan perlengkapan kantor'],
            ['name' => 'Mainan', 'description' => 'Mainan anak-anak dan edukasi'],
            ['name' => 'Otomotif', 'description' => 'Sparepart dan aksesori kendaraan'],
            ['name' => 'Hobi', 'description' => 'Produk untuk hobi dan koleksi'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }

        // Beberapa kategori nonaktif
        Category::factory(2)->inactive()->create();
    }
}
