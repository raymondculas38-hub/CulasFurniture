<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $userId = Auth::id();

        $totalOrders = $this->analyticsService->getUserTotalOrders($userId);
        $completedOrders = $this->analyticsService->getUserCompletedOrders($userId);
        $pendingOrders = $this->analyticsService->getUserPendingOrders($userId);
        $totalSpent = $this->analyticsService->getUserTotalSpent($userId);
        $mostPurchased = $this->analyticsService->getUserMostPurchased($userId, 5);
        $recentOrders = $this->analyticsService->getUserRecentOrders($userId, 5);

        return view('user.analytics', compact(
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalSpent',
            'mostPurchased',
            'recentOrders'
        ));
    }
}
