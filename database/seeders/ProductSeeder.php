<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $livingRoom = Category::where('slug', 'living-room')->first();
        $diningRoom = Category::where('slug', 'dining-room')->first();
        $office = Category::where('slug', 'office-workspace')->first();
        $bedroom = Category::where('slug', 'bedroom')->first();
        $storage = Category::where('slug', 'storage-shelving')->first();
        $entertainment = Category::where('slug', 'entertainment')->first();

        $products = [
            [
                'category_id' => $livingRoom->id,
                'name' => 'Modern Lounge Sofa',
                'slug' => 'modern-lounge-sofa',
                'short_description' => 'A sculptural, ultra-comfortable 3-seater sofa wrapped in textured boucle fabric.',
                'description' => 'Experience unparalleled relaxation with the Modern Lounge Sofa. Handcrafted from kiln-dried solid oak framing and upholstered in stain-resistant Italian boucle wool, this statement piece blends organic curves with architectural elegance. High-density resilient foam core paired with hypoallergenic goose feather filling ensures plush support that maintains its shape over decades of lounging.',
                'price' => 1299.00,
                'sale_price' => 1149.00,
                'stock' => 15,
                'main_image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => true,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1550254478-ead40cc54513?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
            [
                'category_id' => $diningRoom->id,
                'name' => 'Scandinavian Dining Table',
                'slug' => 'scandinavian-dining-table',
                'short_description' => 'Solid white oak dining table with soft-beveled edges and tapered angled legs.',
                'description' => 'The Scandinavian Dining Table celebrates Nordic craftsmanship and clean, unhurried design. Accommodating 6 to 8 guests comfortably, each tabletop is carefully selected from sustainable European White Oak, finished with a water-based non-toxic matte lacquer that highlights the natural grain while providing everyday spill protection.',
                'price' => 849.00,
                'sale_price' => null,
                'stock' => 12,
                'main_image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => true,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1577140917170-285929fb55b7?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
            [
                'category_id' => $office->id,
                'name' => 'Executive Office Chair',
                'slug' => 'executive-office-chair',
                'short_description' => 'Top-grain leather ergonomic chair with lumbar memory support and brushed aluminum base.',
                'description' => 'Engineered for commanding authority and 12-hour ergonomic bliss. The Executive Office Chair combines supple full-grain espresso leather with dynamic lumbar pivoting and synchronous tilt tension. Features silent rolling casters, smooth pneumatic height adjustment, and padded 3D adjustable armrests.',
                'price' => 499.00,
                'sale_price' => 449.00,
                'stock' => 20,
                'main_image' => 'https://images.unsplash.com/photo-1580481077198-c827c4912952?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => true,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1505797149-43b0069ec26b?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1589578228447-e1a4e481c6c8?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
            [
                'category_id' => $bedroom->id,
                'name' => 'Minimalist Bed Frame',
                'slug' => 'minimalist-bed-frame',
                'short_description' => 'Low-profile Japanese-inspired platform bed with integrated floating nightstands.',
                'description' => 'Transform your bedroom into a calm retreat with this solid walnut low-profile platform bed. Designed with reinforced steel slat supports requiring no box spring, chamfered corner joints, and dual floating cantilevered side ledges for nightstand essentials.',
                'price' => 980.00,
                'sale_price' => 899.00,
                'stock' => 8,
                'main_image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => true,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1540518614846-7ede433c4550?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
            [
                'category_id' => $storage->id,
                'name' => 'Wooden Bookshelf',
                'slug' => 'wooden-bookshelf',
                'short_description' => '5-tier solid ash open shelving unit with brass geometric structural accents.',
                'description' => 'A balance of open architectural space and reliable strength. Each tier is crafted from 1.5-inch thick solid ash wood with warm honey stain, supported by subtle brushed brass diagonal bracing. Perfect for displaying curated books, art pieces, ceramics, and indoor greenery.',
                'price' => 620.00,
                'sale_price' => null,
                'stock' => 18,
                'main_image' => 'https://images.unsplash.com/photo-1594040226829-7f251ab46d80?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => false,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1594040226039-756a1186688f?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
            [
                'category_id' => $entertainment->id,
                'name' => 'Modern TV Console',
                'slug' => 'modern-tv-console',
                'short_description' => 'Fluted oak media credenza with soft-close sliding slatted doors and wire pass-throughs.',
                'description' => 'Keep your entertainment area clean and organized with the Modern TV Console. Features tactile vertical tambour oak sliding doors that allow IR remote control signals to pass through even when closed, rear ventilation slots for electronics, and internal adjustable shelving.',
                'price' => 750.00,
                'sale_price' => 690.00,
                'stock' => 10,
                'main_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
                'is_featured' => true,
                'is_active' => true,
                'additional_images' => [
                    'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=1000&q=80',
                ]
            ],
        ];

        foreach ($products as $pData) {
            $additionalImages = $pData['additional_images'] ?? [];
            unset($pData['additional_images']);

            $product = Product::updateOrCreate(['slug' => $pData['slug']], $pData);

            // Seed images
            $product->images()->delete();
            foreach ($additionalImages as $idx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl,
                    'sort_order' => $idx + 1,
                ]);
            }
        }
    }
}
