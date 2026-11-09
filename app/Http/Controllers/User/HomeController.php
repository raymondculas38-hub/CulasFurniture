<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'images'])
            ->take(6)
            ->get();

        $newArrivals = Product::active()
            ->with(['category', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $latestReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(6)
            ->get();

        return view('user.home', compact('featuredProducts', 'newArrivals', 'categories', 'latestReviews'));
    }
}
