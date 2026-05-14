<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingSale;
use App\Models\MarketingTarget;
use App\Models\SalesCommissionRule;
use App\Services\SalesCommissionCalculator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    public function dashboard(Request $request)
    {
        $month = $this->validMonth($request->input('month')) ?: now()->format('Y-m');
        $approved = MarketingSale::where('user_id', auth()->id())
            ->approved()
            ->whereYear('deal_date', substr($month, 0, 4))
            ->whereMonth('deal_date', substr($month, 5, 2));

        $totalSales = (clone $approved)->sum('deal_price');
        $target = MarketingTarget::where(function ($query) {
                $query->where('user_id', auth()->id())->orWhereNull('user_id');
            })
            ->where('target_type', 'monthly')
            ->where('period_year', (int) substr($month, 0, 4))
            ->where('period_month', (int) substr($month, 5, 2))
            ->sum('target_amount');

        $sales = MarketingSale::where('user_id', auth()->id())
            ->latest('deal_date')
            ->take(8)
            ->get();

        return view('marketing.sales.dashboard', [
            'month' => $month,
            'sales' => $sales,
            'stats' => [
                'total_closing' => (clone $approved)->count(),
                'total_sales' => $totalSales,
                'total_commission' => (clone $approved)->sum('commission_total'),
                'target_amount' => $target,
                'target_percent' => $target > 0 ? min(100, round($totalSales / $target * 100, 1)) : 0,
                'pending_sales' => MarketingSale::where('user_id', auth()->id())->where('status', 'pending')->count(),
            ],
        ]);
    }

    public function index(Request $request)
    {
        $sales = MarketingSale::where('user_id', auth()->id())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->latest('deal_date')
            ->paginate(12)
            ->appends($request->query());

        return view('marketing.sales.index', [
            'sales' => $sales,
            'categories' => SalesCommissionRule::CATEGORIES,
            'statuses' => MarketingSale::STATUSES,
        ]);
    }

    public function create()
    {
        return view('marketing.sales.create', [
            'categories' => SalesCommissionRule::CATEGORIES,
        ]);
    }

    public function store(Request $request, SalesCommissionCalculator $calculator)
    {
        $data = $this->validatedData($request);
        $commission = $calculator->calculate($data['category'], (int) $data['deal_price']);

        MarketingSale::create([
            ...$data,
            ...$commission,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('marketing.sales.index')
            ->with('success', 'Data penjualan berhasil dikirim dan menunggu approval admin.');
    }

    public function cancel(MarketingSale $sale)
    {
        abort_unless($sale->user_id === auth()->id(), 403);
        abort_unless($sale->status === 'pending', 422);

        $sale->update(['status' => 'cancel']);

        return back()->with('success', 'Data penjualan berhasil dibatalkan.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'category' => ['required', Rule::in(array_keys(SalesCommissionRule::CATEGORIES))],
            'product_name' => ['required', 'string', 'max:255'],
            'deal_price' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['required', 'string', 'max:255'],
            'deal_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function validMonth(?string $month): ?string
    {
        return is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) ? $month : null;
    }
}
