<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Living Room',
                'slug' => 'living-room',
                'description' => 'Sophisticated sofas, lounge chairs, and accents crafted for ultimate comfort and aesthetic grace.',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Dining Room',
                'slug' => 'dining-room',
                'description' => 'Handcrafted dining tables, chairs, and sideboards designed for memorable gatherings.',
                'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Office & Workspace',
                'slug' => 'office-workspace',
                'description' => 'Ergonomic executive chairs and minimalist desks engineered for high productivity.',
                'image' => 'https://images.unsplash.com/photo-1580481077198-c827c4912952?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Bedroom',
                'slug' => 'bedroom',
                'description' => 'Plush beds, organic wooden frames, and nightstands creating a tranquil sleep sanctuary.',
                'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Storage & Shelving',
                'slug' => 'storage-shelving',
                'description' => 'Modular bookcases, credenzas, and storage solutions combining utility and Scandinavian beauty.',
                'image' => 'https://images.unsplash.com/photo-1594040226829-7f251ab46d80?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Contemporary TV consoles and media centers with integrated cable management.',
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
