<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ASUS',
                'dec' => 'Good performance for students and developers.',
            ],
            [
                'name' => 'Dell',
                'dec' => 'Reliable laptops for work and business.',
            ],
            [
                'name' => 'HP',
                'dec' => 'Affordable and versatile computers.',
            ],
            [
                'name' => 'Lenovo',
                'dec' => 'Popular for productivity and durability.',
            ],
            [
                'name' => 'Acer',
                'dec' => 'Budget-friendly laptops with good features.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['dec' => $category['dec']]
            );
        }
    }
}