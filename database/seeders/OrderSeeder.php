<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $john = User::where('email', 'santi@gmail.com')->first();
        $sarah = User::where('email', 'sarah.jenkins@example.com')->first();

        $sofa = Product::where('slug', 'modern-lounge-sofa')->first();
        $table = Product::where('slug', 'scandinavian-dining-table')->first();
        $chair = Product::where('slug', 'executive-office-chair')->first();
        $bed = Product::where('slug', 'minimalist-bed-frame')->first();

        if (!$john || !$sarah || !$sofa || !$table || !$chair) {
            return;
        }

        // Order 1: Delivered (Santi) -> Contains Sofa & Chair -> Has Reviews
        $order1 = Order::create([
            'order_number' => 'ORD-10928374',
            'user_id' => $john->id,
            'subtotal' => 1149.00 + 449.00,
            'total' => 1149.00 + 449.00,
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'COD',
            'delivery_name' => 'Santi',
            'delivery_phone' => '+1 (555) 234-5678',
            'delivery_address' => '742 Evergreen Terrace',
            'delivery_city' => 'Springfield',
            'delivery_notes' => 'Please ring the doorbell upon arrival.',
            'accepted_at' => now()->subDays(10),
            'preparing_at' => now()->subDays(9),
            'out_for_delivery_at' => now()->subDays(8),
            'delivered_at' => now()->subDays(8),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $sofa->id,
            'product_name' => $sofa->name,
            'price' => 1149.00,
            'quantity' => 1,
            'subtotal' => 1149.00,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $chair->id,
            'product_name' => $chair->name,
            'price' => 449.00,
            'quantity' => 1,
            'subtotal' => 449.00,
        ]);

        // Review from John for the Sofa
        Review::updateOrCreate(
            ['user_id' => $john->id, 'product_id' => $sofa->id, 'order_id' => $order1->id],
            [
                'rating' => 5,
                'comment' => 'Exceptional build quality! The boucle upholstery is soft yet sturdy, and the cushions are supremely comfortable. Delivered on time and assembly was a breeze.',
            ]
        );

        // Review from John for the Chair
        Review::updateOrCreate(
            ['user_id' => $john->id, 'product_id' => $chair->id, 'order_id' => $order1->id],
            [
                'rating' => 5,
                'comment' => 'Transformed my work-from-home routine. Excellent lumbar support and the leather finish looks very high-end.',
            ]
        );

        // Order 2: Delivered (Sarah) -> Contains Dining Table -> Has Review
        $order2 = Order::create([
            'order_number' => 'ORD-28394857',
            'user_id' => $sarah->id,
            'subtotal' => 849.00,
            'total' => 849.00,
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'COD',
            'delivery_name' => 'Sarah Jenkins',
            'delivery_phone' => '+1 (555) 876-5432',
            'delivery_address' => '124 Conch Street',
            'delivery_city' => 'Pacific Grove',
            'accepted_at' => now()->subDays(5),
            'preparing_at' => now()->subDays(4),
            'out_for_delivery_at' => now()->subDays(3),
            'delivered_at' => now()->subDays(3),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $table->id,
            'product_name' => $table->name,
            'price' => 849.00,
            'quantity' => 1,
            'subtotal' => 849.00,
        ]);

        Review::updateOrCreate(
            ['user_id' => $sarah->id, 'product_id' => $table->id, 'order_id' => $order2->id],
            [
                'rating' => 5,
                'comment' => 'The solid oak texture is gorgeous. Clean lines and fits our dining space flawlessly. Highly recommend Santi Furniture!',
            ]
        );

        // Order 3: Out for Delivery (Santi)
        $order3 = Order::create([
            'order_number' => 'ORD-39485726',
            'user_id' => $john->id,
            'subtotal' => 899.00,
            'total' => 899.00,
            'status' => Order::STATUS_OUT_FOR_DELIVERY,
            'payment_method' => 'COD',
            'delivery_name' => 'Santi',
            'delivery_phone' => '+1 (555) 234-5678',
            'delivery_address' => '742 Evergreen Terrace',
            'delivery_city' => 'Springfield',
            'accepted_at' => now()->subDays(2),
            'preparing_at' => now()->subDay(),
            'out_for_delivery_at' => now()->subHours(4),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $bed->id,
            'product_name' => $bed->name,
            'price' => 899.00,
            'quantity' => 1,
            'subtotal' => 899.00,
        ]);

        // Order 4: Pending (Sarah)
        $order4 = Order::create([
            'order_number' => 'ORD-48572619',
            'user_id' => $sarah->id,
            'subtotal' => 690.00,
            'total' => 690.00,
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'COD',
            'delivery_name' => 'Sarah Jenkins',
            'delivery_phone' => '+1 (555) 876-5432',
            'delivery_address' => '124 Conch Street',
            'delivery_city' => 'Pacific Grove',
        ]);

        $tvConsole = Product::where('slug', 'modern-tv-console')->first();
        if ($tvConsole) {
            OrderItem::create([
                'order_id' => $order4->id,
                'product_id' => $tvConsole->id,
                'product_name' => $tvConsole->name,
                'price' => 690.00,
                'quantity' => 1,
                'subtotal' => 690.00,
            ]);
        }
    }
}
