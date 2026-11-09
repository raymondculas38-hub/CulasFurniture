<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $totalSales = $this->analyticsService->getTotalSales();
        $totalOrders = $this->analyticsService->getTotalOrders();
        $totalCustomers = $this->analyticsService->getTotalCustomers();
        $totalProducts = $this->analyticsService->getTotalProducts();

        $orderStatuses = $this->analyticsService->getOrdersByStatus();
        $recentOrders = $this->analyticsService->getRecentOrders(8);
        $bestSellers = $this->analyticsService->getBestSellingProducts(5);
        $monthlySales = $this->analyticsService->getMonthlySales(6);

        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'orderStatuses',
            'recentOrders',
            'bestSellers',
            'monthlySales',
            'lowStockProducts'
        ));
    }
}
