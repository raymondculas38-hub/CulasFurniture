<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
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
        $bestSellers = $this->analyticsService->getBestSellingProducts(10);
        $mostRequested = $this->analyticsService->getMostRequestedProducts(10);
        $averageRatings = $this->analyticsService->getAverageProductRatings();
        $monthlySales = $this->analyticsService->getMonthlySales(12);
        $weeklyOrders = $this->analyticsService->getWeeklyOrders();

        return view('admin.analytics', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'orderStatuses',
            'bestSellers',
            'mostRequested',
            'averageRatings',
            'monthlySales',
            'weeklyOrders'
        ));
    }
}
