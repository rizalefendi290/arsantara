<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => Listing::where('user_id', $user->id)->count(),
            'pending' => Listing::where('user_id', $user->id)->where('status', 'pending')->count(),
            'active' => Listing::where('user_id', $user->id)->where('status', 'aktif')->count(),
            'sold' => Listing::where('user_id', $user->id)->where('status', 'sold')->count(),
        ];

        $listings = Listing::with(['category', 'images'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('agent.dashboard', compact('stats', 'listings'));
    }
}
