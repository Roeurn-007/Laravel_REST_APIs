<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class EcommerceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customers = collect([
            ['name' => 'John Customer', 'email' => 'user@example.com'],
            ['name' => 'Sokha Chan', 'email' => 'sokha@example.com'],
            ['name' => 'Dara Kim', 'email' => 'dara@example.com'],
            ['name' => 'Maly Heng', 'email' => 'maly@example.com'],
            ['name' => 'Vicheka Lim', 'email' => 'vicheka@example.com'],
        ])->map(fn ($customer) => User::updateOrCreate(
            ['email' => $customer['email']],
            [
                'name' => $customer['name'],
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        ));

        $products = Product::orderBy('id')->get();

        if ($products->count() < 4 || $customers->count() < 4) {
            return;
        }

        $orders = [
            ['user' => 0, 'status' => 'completed', 'date' => now()->subDays(27), 'items' => [[0, 1], [1, 1]]],
            ['user' => 1, 'status' => 'pending', 'date' => now()->subDays(19), 'items' => [[2, 2]]],
            ['user' => 2, 'status' => 'processing', 'date' => now()->subDays(13), 'items' => [[3, 1], [4, 1]]],
            ['user' => 3, 'status' => 'delivered', 'date' => now()->subDays(7), 'items' => [[1, 2], [2, 1]]],
            ['user' => 4, 'status' => 'cancelled', 'date' => now()->subDays(4), 'items' => [[4, 1]]],
            ['user' => 0, 'status' => 'shipped', 'date' => now()->subDay(), 'items' => [[0, 1], [3, 1]]],
        ];

        foreach ($orders as $index => $orderData) {
            $total = collect($orderData['items'])->sum(function ($item) use ($products) {
                $product = $products[$item[0]];

                return $product->price * $item[1];
            });

            $order = Order::updateOrCreate(
                [
                    'user_id' => $customers[$orderData['user']]->id,
                    'created_at' => $orderData['date'],
                ],
                [
                    'total_price' => $total,
                    'status' => $orderData['status'],
                    'shipping_address' => 'Phnom Penh, Cambodia, Street ' . (100 + $index),
                    'updated_at' => $orderData['date'],
                ]
            );

            foreach ($orderData['items'] as $item) {
                $product = $products[$item[0]];

                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => $item[1],
                        'price' => $product->price,
                    ]
                );
            }
        }

        $cartItems = [
            [0, 2, 1],
            [1, 0, 2],
            [2, 4, 1],
            [3, 1, 1],
            [4, 3, 2],
        ];

        foreach ($cartItems as $item) {
            CartItem::updateOrCreate(
                ['user_id' => $customers[$item[0]]->id, 'product_id' => $products[$item[1]]->id],
                ['quantity' => $item[2]]
            );
        }

        $wishlists = [
            [0, 3],
            [1, 2],
            [2, 0],
            [3, 4],
            [4, 1],
        ];

        foreach ($wishlists as $item) {
            Wishlist::updateOrCreate([
                'user_id' => $customers[$item[0]]->id,
                'product_id' => $products[$item[1]]->id,
            ]);
        }

        $reviews = [
            [0, 0, 5, 'Great laptop for daily development work.'],
            [1, 1, 4, 'Reliable performance and clean design.'],
            [2, 2, 4, 'Good value for students.'],
            [3, 3, 5, 'Excellent keyboard and build quality.'],
            [4, 4, 3, 'Affordable option with decent speed.'],
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate(
                ['user_id' => $customers[$review[0]]->id, 'product_id' => $products[$review[1]]->id],
                [
                    'rating' => $review[2],
                    'comment' => $review[3],
                    'created_at' => Carbon::now()->subDays(10 - $review[0]),
                    'updated_at' => Carbon::now()->subDays(10 - $review[0]),
                ]
            );
        }
    }
}
