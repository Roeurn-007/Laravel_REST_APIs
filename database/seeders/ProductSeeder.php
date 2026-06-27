<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'ASUS VivoBook',
                'description' => 'A sleek and lightweight laptop perfect for students and developers. Features a 15.6-inch Full HD display, Intel Core i5 processor, 8GB RAM, and 512GB SSD for fast performance.',
                'price' => 760,
                'stock' => 20,
                'category_id' => 1,
            ],
            [
                'name' => 'Dell Inspiron',
                'description' => 'Reliable laptop designed for work and business. Equipped with Intel Core i7, 16GB RAM, 1TB SSD, and long-lasting battery for productivity on the go.',
                'price' => 850,
                'stock' => 15,
                'category_id' => 2,
            ],
            [
                'name' => 'HP Pavilion',
                'description' => 'Affordable and versatile laptop for everyday use. Features AMD Ryzen 5 processor, 8GB RAM, 256GB SSD, and a vibrant 14-inch display perfect for entertainment and work.',
                'price' => 720,
                'stock' => 10,
                'category_id' => 3,
            ],
            [
                'name' => 'Lenovo ThinkPad',
                'description' => 'Built for productivity and durability. This business-class laptop features Intel Core i5, 16GB RAM, 512GB SSD, and legendary ThinkPad keyboard for comfortable typing.',
                'price' => 950,
                'stock' => 8,
                'category_id' => 4,
            ],
            [
                'name' => 'Acer Aspire',
                'description' => 'Budget-friendly laptop with good features for everyday computing. Comes with Intel Core i3, 4GB RAM, 256GB SSD, and a 15.6-inch display perfect for students and home use.',
                'price' => 680,
                'stock' => 12,
                'category_id' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'category_id' => $product['category_id'],
                ]
            );
        }
    }
}