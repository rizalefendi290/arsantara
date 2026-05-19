<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\SiteVisit;
use App\Models\User;
use App\Models\Category;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalListings = Listing::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();

        $listings = Listing::with(['images', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $activeVisitors = SiteVisit::where('visited_at', '>=', now()->subMinutes(5))
            ->distinct('session_id')
            ->count('session_id');

        $visitorStats = [
            'today' => SiteVisit::where('visited_at', '>=', now()->startOfDay())->distinct('session_id')->count('session_id'),
            'week' => SiteVisit::where('visited_at', '>=', now()->startOfWeek())->distinct('session_id')->count('session_id'),
            'month' => SiteVisit::where('visited_at', '>=', now()->startOfMonth())->distinct('session_id')->count('session_id'),
            'total' => SiteVisit::distinct('session_id')->count('session_id'),
        ];

        $trendStart = now()->subDays(13)->startOfDay();
        $trendPeriod = CarbonPeriod::create($trendStart, now()->startOfDay());

        $visitorTrend = SiteVisit::selectRaw('DATE(visited_at) as date, COUNT(DISTINCT session_id) as total')
            ->where('visited_at', '>=', $trendStart)
            ->groupBy('date')
            ->pluck('total', 'date');

        $listingTrend = Listing::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $trendStart)
            ->groupBy('date')
            ->pluck('total', 'date');

        $userTrend = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $trendStart)
            ->groupBy('date')
            ->pluck('total', 'date');

        $dashboardChart = [
            'labels' => [],
            'visitors' => [],
            'listings' => [],
            'users' => [],
        ];

        foreach ($trendPeriod as $date) {
            $key = $date->toDateString();

            $dashboardChart['labels'][] = $date->format('d M');
            $dashboardChart['visitors'][] = (int) ($visitorTrend[$key] ?? 0);
            $dashboardChart['listings'][] = (int) ($listingTrend[$key] ?? 0);
            $dashboardChart['users'][] = (int) ($userTrend[$key] ?? 0);
        }

        $topListings = Listing::with(['images', 'category', 'user'])
            ->withCount('views')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $activeAgents = User::whereIn('role', ['agen', 'pemilik'])
            ->withCount([
                'listings',
                'listings as active_listings_count' => fn ($query) => $query->where('status', 'aktif'),
            ])
            ->orderByDesc('listings_count')
            ->take(5)
            ->get();

        $listingStatusCounts = Listing::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $listingStatusChart = [
            'labels' => $listingStatusCounts->keys()->map(fn ($status) => ucfirst($status))->values()->all(),
            'data' => $listingStatusCounts->values()->all(),
        ];

        $categories = Category::withCount('listings')
            ->orderBy('id')
            ->get();

        return view('admin.dashboard', compact(
            'totalListings',
            'totalUsers',
            'totalCategories',
            'listings',
            'activeVisitors',
            'visitorStats',
            'topListings',
            'activeAgents',
            'listingStatusCounts',
            'categories',
            'dashboardChart',
            'listingStatusChart'
        ));
    }

    public function toggleCategory(Request $request, Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        return back()->with(
            'success',
            'Kategori '.$category->name.' berhasil '.($category->is_active ? 'diaktifkan' : 'dinonaktifkan').'.'
        );
    }
}
