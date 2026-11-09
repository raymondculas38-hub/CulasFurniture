<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    // ====== ADMIN ANALYTICS ======

    public function getTotalSales(): float
    {
        return (float) Order::where('status', Order::STATUS_DELIVERED)->sum('total');
    }

    public function getTotalRevenue(): float
    {
        return $this->getTotalSales();
    }

    public function getTotalCustomers(): int
    {
        return User::where('role', 'user')->count();
    }

    public function getTotalProducts(): int
    {
        return Product::count();
    }

    public function getTotalOrders(): int
    {
        return Order::count();
    }

    public function getOrdersByStatus(): array
    {
        return [
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'accepted' => Order::where('status', Order::STATUS_ACCEPTED)->count(),
            'preparing' => Order::where('status', Order::STATUS_PREPARING)->count(),
            'out_for_delivery' => Order::where('status', Order::STATUS_OUT_FOR_DELIVERY)->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];
    }

    public function getBestSellingProducts(int $limit = 5)
    {
        $productTotals = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) {
                $q->whereIn('status', [Order::STATUS_ACCEPTED, Order::STATUS_PREPARING, Order::STATUS_OUT_FOR_DELIVERY, Order::STATUS_DELIVERED]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        if ($productTotals->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $productTotals->pluck('product_id'))
            ->with('category')
            ->get()
            ->keyBy('id');

        return $productTotals->map(function ($item) use ($products) {
            $product = $products->get($item->product_id);
            if ($product) {
                $product->total_sold = (int) $item->total_sold;
                return $product;
            }
            return null;
        })->filter()->values();
    }

    public function getMostRequestedProducts(int $limit = 5)
    {
        $itemCounts = OrderItem::select('product_id', DB::raw('COUNT(id) as order_count'))
            ->groupBy('product_id')
            ->orderByDesc('order_count')
            ->limit($limit)
            ->get();

        if ($itemCounts->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $itemCounts->pluck('product_id'))
            ->with('category')
            ->get()
            ->keyBy('id');

        return $itemCounts->map(function ($item) use ($products) {
            $product = $products->get($item->product_id);
            if ($product) {
                $product->order_count = (int) $item->order_count;
                return $product;
            }
            return null;
        })->filter()->values();
    }

    public function getAverageProductRatings()
    {
        $reviewStats = Review::select('product_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as review_count'))
            ->groupBy('product_id')
            ->orderByDesc('avg_rating')
            ->get();

        if ($reviewStats->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $reviewStats->pluck('product_id'))
            ->with('category')
            ->get()
            ->keyBy('id');

        return $reviewStats->map(function ($item) use ($products) {
            $product = $products->get($item->product_id);
            if ($product) {
                $product->avg_rating = round((float) $item->avg_rating, 1);
                $product->review_count = (int) $item->review_count;
                return $product;
            }
            return null;
        })->filter()->values();
    }

    public function getMonthlySales(int $months = 12): array
    {
        $data = Order::where('status', Order::STATUS_DELIVERED)
            ->where('delivered_at', '>=', now()->subMonths($months))
            ->select(
                DB::raw('MONTH(delivered_at) as month'),
                DB::raw('YEAR(delivered_at) as year'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy(DB::raw('YEAR(delivered_at)'), DB::raw('MONTH(delivered_at)'))
            ->orderBy(DB::raw('YEAR(delivered_at)'))
            ->orderBy(DB::raw('MONTH(delivered_at)'))
            ->get();

        $labels = [];
        $sales = [];
        $orders = [];

        foreach ($data as $row) {
            $labels[] = date('M Y', mktime(0, 0, 0, $row->month, 1, $row->year));
            $sales[] = round((float) $row->total_sales, 2);
            $orders[] = (int) $row->total_orders;
        }

        return compact('labels', 'sales', 'orders');
    }

    public function getWeeklyOrders(): array
    {
        $data = Order::where('created_at', '>=', now()->subWeeks(8))
            ->select(
                DB::raw('YEARWEEK(created_at, 1) as week_num'),
                DB::raw('MIN(DATE(created_at)) as week_start'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->get();

        $labels = [];
        $orders = [];

        foreach ($data as $row) {
            $labels[] = date('M d', strtotime($row->week_start));
            $orders[] = (int) $row->total_orders;
        }

        return compact('labels', 'orders');
    }

    public function getRecentOrders(int $limit = 10)
    {
        return Order::with('user', 'items')
            ->latest()
            ->limit($limit)
            ->get();
    }

    // ====== USER ANALYTICS ======

    public function getUserTotalOrders(int $userId): int
    {
        return Order::where('user_id', $userId)->count();
    }

    public function getUserCompletedOrders(int $userId): int
    {
        return Order::where('user_id', $userId)->where('status', Order::STATUS_DELIVERED)->count();
    }

    public function getUserPendingOrders(int $userId): int
    {
        return Order::where('user_id', $userId)->where('status', Order::STATUS_PENDING)->count();
    }

    public function getUserTotalSpent(int $userId): float
    {
        return (float) Order::where('user_id', $userId)->where('status', Order::STATUS_DELIVERED)->sum('total');
    }

    public function getUserMostPurchased(int $userId, int $limit = 5)
    {
        $itemTotals = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_purchased'))
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', Order::STATUS_DELIVERED);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_purchased')
            ->limit($limit)
            ->get();

        if ($itemTotals->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $itemTotals->pluck('product_id'))
            ->with('category')
            ->get()
            ->keyBy('id');

        return $itemTotals->map(function ($item) use ($products) {
            $product = $products->get($item->product_id);
            if ($product) {
                $product->total_purchased = (int) $item->total_purchased;
                return $product;
            }
            return null;
        })->filter()->values();
    }

    public function getUserRecentOrders(int $userId, int $limit = 5)
    {
        return Order::where('user_id', $userId)
            ->with('items.product')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
