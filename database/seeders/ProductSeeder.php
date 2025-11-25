<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $suppliers = Supplier::where('is_active', true)->get();

        // Mapping kategori dengan produk yang sesuai
        $categoryProducts = [
            'Elektronik' => [
                'TV LED Samsung 32 Inch', 'Kulkas 2 Pintu LG', 'Mesin Cuci Sharp 8kg',
                'AC Panasonic 1/2 PK', 'Blender Miyako', 'Rice Cooker Cosmos',
                'Kipas Angin Cosmos', 'Setrika Philips', 'Speaker JBL Portable',
                'Headphone Sony', 'Smartphone Xiaomi Redmi', 'Tablet Samsung Galaxy',
                'Laptop ASUS Vivobook', 'Monitor LG 24 Inch', 'Printer Epson L3210'
            ],
            'Pakaian' => [
                'Kemeja Katun Pria', 'Celana Jeans Levi\'s', 'Kaos Oblong Basic',
                'Jaket Hoodie Uniqlo', 'Dress Wanita Casual', 'Rok Wanita Trendy',
                'Sepatu Sneakers Nike', 'Sendal Swallow', 'Tas Ransel Eiger',
                'Topi Baseball', 'Kacamata Ray-Ban', 'Jam Tangan Casio',
                'Sweter Rajut Wanita', 'Celana Training Pria', 'Kaus Kaki Sport'
            ],
            'Makanan & Minuman' => [
                'Indomie Goreng', 'Kopi Kapal Api', 'Teh Botol Sosro',
                'Biscuit Roma Kelapa', 'Choki-Choki', 'Permen Kopiko',
                'Susu Ultra Milk', 'Air Mineral Aqua', 'Minyak Goreng Bimoli',
                'Gula Gulaku', 'Kecap Bango', 'Sambal ABC',
                'Roti Tawar Sari Roti', 'Mie Sedap Ayam Bawang', 'Kerupuk Udang'
            ],
            'Perabotan' => [
                'Sofa Minimalis 3 Seater', 'Meja Makan Kayu Jati', 'Kursi Kantor Ergonomis',
                'Lemari Pakaian 3 Pintu', 'Rak Buku Minimalis', 'Meja TV Modern',
                'Tempat Tidur Queen Size', 'Spring Bed Comforta', 'Lemari Es',
                'Kitchen Set Minimalis', 'Wastafel Marmer', 'Lampu Gantung Kristal',
                'Kursi Tamu Minimalis', 'Meja Rias Kayu', 'Lemari Hias Kaca'
            ],
            'Olahraga' => [
                'Sepeda Polygon', 'Treadmill Magnetic', 'Dumbell Set 20kg',
                'Matras Yoga', 'Bola Sepak Specs', 'Raket Badminton Yonex',
                'Sepatu Lari Nike', 'Jersey Bola Adidas', 'Tas Gym',
                'Botol Minum Tumblr', 'Hand Grip', 'Skiping Rope',
                'Sepatu Futsal Specs', 'Sarung Tinju', 'Bola Basket Molten'
            ],
            'Kesehatan' => [
                'Masker Bedah 3 Ply', 'Hand Sanitizer 100ml', 'Thermometer Digital',
                'Vitamin C 1000mg', 'Minyak Kayu Putih', 'Obat Pusing Bodrex',
                'Sabun Lifebuoy', 'Shampoo Clear', 'Pasta Gigi Pepsodent',
                'Body Lotion Nivea', 'Deodorant Rexona', 'Kapas Pembersih',
                'Obat Batuk Konidin', 'Minyak Telon', 'Koyo Salonpas'
            ],
            'Buku & Alat Tulis' => [
                'Buku Tulis Sinar Dunia', 'Pulpen Standard', 'Pensil 2B Faber-Castell',
                'Penghapus Staedtler', 'Penggaris 30cm', 'Stabilo Boss',
                'Map Plastik', 'Notebook A5', 'Kalkulator Casio',
                'Gunting Joyko', 'Lem Fox', 'Amplop Coklat',
                'Buku Gambar A3', 'Spidol Whiteboard', 'Stapler Kenko'
            ],
            'Mainan' => [
                'Lego Classic', 'Puzzle 1000 Keping', 'Boneka Barbie',
                'Mobil Remote Control', 'Robot Transformer', 'Play-Doh Set',
                'Action Figure Gundam', 'Board Game Monopoly', 'Rubik\'s Cube',
                'Mainan Edukasi Kayu', 'Slime Kit', 'Nerf Gun',
                'Boneka Hello Kitty', 'Mobil-mobilan Hot Wheels', 'Pistol Air'
            ],
            'Otomotif' => [
                'Oli Mobil Shell', 'Ban Mobil Bridgestone', 'Aki GS Battery',
                'Wiper Blade Bosch', 'Lampu Mobil Philips', 'Kampas Rem',
                'Air Radiator', 'Semir Ban', 'Sabun Cuci Mobil',
                'Charger Aki', 'Vacuum Cleaner Portable', 'Karpet Mobil',
                'Busi NGK', 'Filter Udara', 'Minyak Rem DOT 4'
            ],
            'Hobi' => [
                'Kamera Canon DSLR', 'Lensa 50mm', 'Tripod Kamera',
                'Gitar Akustik Yamaha', 'Ukulele Mahogany', 'Cat Air Faber-Castell',
                'Kanvas Lukis 40x60', 'Kuas Lukis Set', 'Tanaman Hias Monstera',
                'Pot Bunga Keramik', 'Aquarium 60cm', 'Ikan Koi',
                'Buku Mewarnai Dewasa', 'Set Alat Jahit', 'Bibit Tanaman Herbal'
            ]
        ];

        // Buat produk untuk setiap kategori
        foreach ($categories as $category) {
            $categoryName = $category->name;

            if (isset($categoryProducts[$categoryName])) {
                $products = $categoryProducts[$categoryName];

                // Buat 5-8 produk untuk setiap kategori
                $productCount = rand(5, 8);

                // Acak urutan produk agar lebih bervariasi setiap kali seeding
                shuffle($products);
                $selectedProducts = array_slice($products, 0, $productCount);

                foreach ($selectedProducts as $productName) {
                    $this->createProductForCategory($productName, $category, $suppliers->random());
                }
            }
        }

        // Beberapa produk dengan stok rendah
        $this->createLowStockProducts($categories, $suppliers);

        // Beberapa produk habis
        $this->createOutOfStockProducts($categories, $suppliers);

        // Beberapa produk nonaktif
        $this->createInactiveProducts($categories, $suppliers);
    }

    /**
     * Buat produk untuk kategori tertentu
     */
    private function createProductForCategory(string $productName, Category $category, Supplier $supplier): void
    {
        $purchasePrice = $this->getPriceRangeForCategory($category->name);
        $profitMargin = rand(30, 80); // Margin 30-80%
        $sellingPrice = $purchasePrice * (1 + ($profitMargin / 100));

        Product::create([
            'name' => $productName,
            'sku' => 'SKU-' . rand(10000, 99999),
            'barcode' => '89' . rand(100000000000, 999999999999),
            'description' => $this->generateCategoryDescription($productName, $category->name),
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'purchase_price' => $purchasePrice,
            'selling_price' => round($sellingPrice, -3), // Bulatkan ke ribuan
            'profit_margin' => $profitMargin,
            'stock' => rand(20, 100),
            'min_stock' => rand(5, 15),
            'max_stock' => rand(100, 300),
            'unit' => $this->getUnitForCategory($category->name),
            'is_active' => true,
            'is_available' => true,
        ]);
    }

    /**
     * Tentukan range harga berdasarkan kategori
     */
    private function getPriceRangeForCategory(string $categoryName): int
    {
        return match($categoryName) {
            'Elektronik' => rand(500000, 5000000),
            'Pakaian' => rand(50000, 500000),
            'Makanan & Minuman' => rand(5000, 50000),
            'Perabotan' => rand(300000, 3000000),
            'Olahraga' => rand(100000, 2000000),
            'Kesehatan' => rand(10000, 200000),
            'Buku & Alat Tulis' => rand(5000, 100000),
            'Mainan' => rand(25000, 500000),
            'Otomotif' => rand(25000, 1000000),
            'Hobi' => rand(50000, 3000000),
            default => rand(10000, 500000)
        };
    }

    /**
     * Tentukan unit berdasarkan kategori
     */
    private function getUnitForCategory(string $categoryName): string
    {
        return match($categoryName) {
            'Elektronik', 'Perabotan' => 'unit',
            'Makanan & Minuman' => 'pcs',
            'Pakaian', 'Buku & Alat Tulis', 'Mainan', 'Kesehatan' => 'pcs',
            'Olahraga', 'Otomotif', 'Hobi' => 'pcs',
            default => 'pcs'
        };
    }

    /**
     * Generate deskripsi berdasarkan kategori
     */
    private function generateCategoryDescription(string $productName, string $categoryName): string
    {
        $descriptions = [
            'Elektronik' => [
                'Produk elektronik berkualitas tinggi dengan garansi resmi.',
                'Teknologi terkini dengan efisiensi energi terbaik.',
                'Desain modern dengan fitur lengkap untuk kebutuhan sehari-hari.'
            ],
            'Pakaian' => [
                'Bahan nyaman dan berkualitas, cocok untuk berbagai occasion.',
                'Desain trendy dan fashionable dengan bahan premium.',
                'Kualitas terbaik dengan harga terjangkau.'
            ],
            'Makanan & Minuman' => [
                'Produk makanan segar dan sehat dengan rasa terbaik.',
                'Kualitas premium dengan bahan-bahan pilihan.',
                'Cita rasa autentik Indonesia yang disukai banyak orang.'
            ],
            'Perabotan' => [
                'Furniture berkualitas dengan desain elegan dan modern.',
                'Material kokoh dan tahan lama, cocok untuk rumah dan kantor.',
                'Kenyamanan maksimal dengan desain yang functional.'
            ],
            'Olahraga' => [
                'Alat olahraga profesional untuk performa terbaik.',
                'Didesain khusus untuk kenyamanan dan keamanan berolahraga.',
                'Kualitas sport gear terbaik untuk atlet dan hobby.'
            ],
            'Kesehatan' => [
                'Produk kesehatan dengan standar keamanan tinggi.',
                'Diformulasikan khusus untuk perawatan tubuh terbaik.',
                'Kualitas terjamin dengan bahan-bahan alami.'
            ],
            'Buku & Alat Tulis' => [
                'Alat tulis berkualitas untuk kebutuhan sekolah dan kantor.',
                'Produk edukasi dengan kualitas terbaik untuk belajar.',
                'Desain functional dengan kenyamanan penggunaan maksimal.'
            ],
            'Mainan' => [
                'Mainan edukatif dan aman untuk anak-anak.',
                'Didesain untuk mengembangkan kreativitas dan imajinasi.',
                'Kualitas terbaik dengan bahan yang aman untuk anak.'
            ],
            'Otomotif' => [
                'Sparepart dan aksesori otomotif dengan kualitas terbaik.',
                'Kompatibel dengan berbagai jenis kendaraan.',
                'Kualitas orisinil dengan performa optimal.'
            ],
            'Hobi' => [
                'Alat dan perlengkapan untuk menyalurkan hobi dengan baik.',
                'Kualitas profesional untuk hasil yang memuaskan.',
                'Didesain khusus untuk penggemar hobi sejati.'
            ]
        ];

        $categoryDesc = $descriptions[$categoryName] ?? ['Produk berkualitas dengan harga terjangkau.'];
        return $productName . '. ' . $categoryDesc[array_rand($categoryDesc)];
    }

    /**
     * Buat produk dengan stok rendah
     */
    private function createLowStockProducts($categories, $suppliers): void
    {
        $lowStockCategories = $categories->random(3);

        foreach ($lowStockCategories as $category) {
            Product::create([
                'name' => $this->getProductNameForCategory($category->name) . ' - Stok Menipis',
                'sku' => 'SKU-LOW-' . rand(1000, 9999),
                'barcode' => '89' . rand(100000000000, 999999999999),
                'description' => 'Produk populer dengan stok terbatas.',
                'category_id' => $category->id,
                'supplier_id' => $suppliers->random()->id,
                'purchase_price' => $this->getPriceRangeForCategory($category->name),
                'selling_price' => $this->getPriceRangeForCategory($category->name) * 1.5,
                'profit_margin' => 50,
                'stock' => rand(1, 5),
                'min_stock' => 10,
                'max_stock' => 100,
                'unit' => $this->getUnitForCategory($category->name),
                'is_active' => true,
                'is_available' => true,
            ]);
        }
    }

    /**
     * Buat produk habis
     */
    private function createOutOfStockProducts($categories, $suppliers): void
    {
        $outOfStockCategories = $categories->random(2);

        foreach ($outOfStockCategories as $category) {
            Product::create([
                'name' => $this->getProductNameForCategory($category->name) . ' - Stok Habis',
                'sku' => 'SKU-OUT-' . rand(1000, 9999),
                'barcode' => '89' . rand(100000000000, 999999999999),
                'description' => 'Produk sedang tidak tersedia, coming soon.',
                'category_id' => $category->id,
                'supplier_id' => $suppliers->random()->id,
                'purchase_price' => $this->getPriceRangeForCategory($category->name),
                'selling_price' => $this->getPriceRangeForCategory($category->name) * 1.5,
                'profit_margin' => 50,
                'stock' => 0,
                'min_stock' => 10,
                'max_stock' => 100,
                'unit' => $this->getUnitForCategory($category->name),
                'is_active' => true,
                'is_available' => false,
            ]);
        }
    }

    /**
     * Buat produk nonaktif
     */
    private function createInactiveProducts($categories, $suppliers): void
    {
        $inactiveCategories = $categories->random(2);

        foreach ($inactiveCategories as $category) {
            Product::create([
                'name' => $this->getProductNameForCategory($category->name) . ' - Discontinued',
                'sku' => 'SKU-DIS-' . rand(1000, 9999),
                'barcode' => '89' . rand(100000000000, 999999999999),
                'description' => 'Produk sudah tidak diproduksi lagi.',
                'category_id' => $category->id,
                'supplier_id' => $suppliers->random()->id,
                'purchase_price' => $this->getPriceRangeForCategory($category->name),
                'selling_price' => $this->getPriceRangeForCategory($category->name) * 1.5,
                'profit_margin' => 50,
                'stock' => 0,
                'min_stock' => 0,
                'max_stock' => 0,
                'unit' => $this->getUnitForCategory($category->name),
                'is_active' => false,
                'is_available' => false,
            ]);
        }
    }

    /**
     * Dapatkan nama produk untuk kategori tertentu
     */
    private function getProductNameForCategory(string $categoryName): string
    {
        $categoryProducts = [
            'Elektronik' => ['TV LED', 'Kulkas', 'Mesin Cuci', 'AC', 'Blender'],
            'Pakaian' => ['Kemeja', 'Celana', 'Kaos', 'Jaket', 'Dress'],
            'Makanan & Minuman' => ['Indomie', 'Kopi', 'Teh', 'Biscuit', 'Susu'],
            'Perabotan' => ['Sofa', 'Meja', 'Kursi', 'Lemari', 'Rak'],
            'Olahraga' => ['Sepeda', 'Treadmill', 'Dumbell', 'Matras', 'Bola'],
            'Kesehatan' => ['Masker', 'Hand Sanitizer', 'Vitamin', 'Obat', 'Sabun'],
            'Buku & Alat Tulis' => ['Buku', 'Pulpen', 'Pensil', 'Penggaris', 'Notebook'],
            'Mainan' => ['Lego', 'Puzzle', 'Boneka', 'Mobil', 'Robot'],
            'Otomotif' => ['Oli', 'Ban', 'Aki', 'Lampu', 'Kampas'],
            'Hobi' => ['Kamera', 'Gitar', 'Cat', 'Kanvas', 'Tanaman']
        ];

        $products = $categoryProducts[$categoryName] ?? ['Produk'];
        return $products[array_rand($products)];
    }
}
