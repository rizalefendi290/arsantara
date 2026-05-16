<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingSale;
use App\Models\MarketingTarget;
use App\Models\SalesCommissionRule;
use App\Models\User;
use App\Services\SalesCommissionCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SalesManagementController extends Controller
{
    public function dashboard(Request $request)
    {
        $month = $this->validMonth($request->input('month')) ?: now()->format('Y-m');
        $salesQuery = $this->filteredSalesQuery($request, $month);
        $approvedSales = (clone $salesQuery)->approved();
        $targetAmount = $this->targetAmountForPeriod($request, $month);
        $totalSales = (clone $approvedSales)->sum('deal_price');

        $ranking = (clone $approvedSales)
            ->selectRaw('user_id, SUM(deal_price) as total_sales, SUM(commission_total) as total_commission, COUNT(*) as total_closing')
            ->with('marketing:id,name,email,phone,profile_photo,is_active')
            ->groupBy('user_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();

        $chart = (clone $approvedSales)
            ->selectRaw('DATE(deal_date) as date, SUM(deal_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.sales.dashboard', [
            'month' => $month,
            'categories' => SalesCommissionRule::CATEGORIES,
            'marketings' => $this->marketingUsers(),
            'stats' => [
                'total_sales' => $totalSales,
                'total_commission' => (clone $approvedSales)->sum('commission_total'),
                'total_marketing' => User::where('role', 'marketing')->count(),
                'target_amount' => $targetAmount,
                'target_percent' => $targetAmount > 0 ? min(100, round($totalSales / $targetAmount * 100, 1)) : 0,
                'pending_sales' => MarketingSale::where('status', 'pending')->count(),
            ],
            'ranking' => $ranking,
            'chart' => $chart,
        ]);
    }

    public function sales(Request $request)
    {
        $sales = $this->filteredSalesQuery($request)
            ->with(['marketing:id,name,email,phone', 'approver:id,name'])
            ->latest('deal_date')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.sales.sales', [
            'sales' => $sales,
            'categories' => SalesCommissionRule::CATEGORIES,
            'statuses' => MarketingSale::STATUSES,
            'marketings' => $this->marketingUsers(),
        ]);
    }

    public function approve(MarketingSale $sale, SalesCommissionCalculator $calculator)
    {
        $sale->update([
            ...$calculator->calculate($sale->category, $sale->deal_price),
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_note' => null,
        ]);

        return back()->with('success', 'Penjualan marketing berhasil disetujui.');
    }

    public function reject(Request $request, MarketingSale $sale)
    {
        $data = $request->validate([
            'rejection_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $sale->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_note' => $data['rejection_note'] ?? null,
        ]);

        return back()->with('success', 'Penjualan marketing berhasil ditolak.');
    }

    public function cancel(MarketingSale $sale)
    {
        $sale->update([
            'status' => 'cancel',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Penjualan marketing berhasil dibatalkan.');
    }

    public function marketing()
    {
        $marketings = User::where('role', 'marketing')
            ->withCount(['marketingSales as approved_sales_count' => fn ($query) => $query->where('status', 'approved')])
            ->withSum(['marketingSales as approved_sales_sum' => fn ($query) => $query->where('status', 'approved')], 'deal_price')
            ->latest()
            ->paginate(15);

        return view('admin.sales.marketing', compact('marketings'));
    }

    public function storeMarketing(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'role' => 'marketing',
            'status' => 'approved',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Data marketing berhasil ditambahkan.');
    }

    public function updateMarketing(Request $request, User $user)
    {
        abort_unless($user->role === 'marketing', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return back()->with('success', 'Data marketing berhasil diperbarui.');
    }

    public function commissionRules()
    {
        $rules = SalesCommissionRule::orderBy('category')->get()->keyBy('category');

        return view('admin.sales.commissions', [
            'categories' => SalesCommissionRule::CATEGORIES,
            'rules' => $rules,
        ]);
    }

    public function updateCommissionRules(Request $request)
    {
        $data = $request->validate([
            'rules' => ['required', 'array'],
            'rules.*.commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rules.*.commission_fixed' => ['nullable', 'numeric', 'min:0'],
            'rules.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach (SalesCommissionRule::CATEGORIES as $category => $label) {
            $rule = $data['rules'][$category] ?? [];
            SalesCommissionRule::updateOrCreate(
                ['category' => $category],
                [
                    'commission_percent' => $rule['commission_percent'] ?? 0,
                    'commission_fixed' => $rule['commission_fixed'] ?? 0,
                    'is_active' => (bool) ($rule['is_active'] ?? false),
                ]
            );
        }

        return back()->with('success', 'Master komisi berhasil diperbarui.');
    }

    public function targets()
    {
        $targets = MarketingTarget::with('marketing:id,name,email')
            ->latest()
            ->paginate(15);

        return view('admin.sales.targets', [
            'targets' => $targets,
            'marketings' => $this->marketingUsers(),
            'types' => MarketingTarget::TYPES,
        ]);
    }

    public function storeTarget(Request $request)
    {
        $data = $this->validateTarget($request);
        MarketingTarget::create($data);

        return back()->with('success', 'Target penjualan berhasil disimpan.');
    }

    public function destroyTarget(MarketingTarget $target)
    {
        $target->delete();

        return back()->with('success', 'Target penjualan berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $sales = $this->filteredSalesQuery($request)->with('marketing')->latest('deal_date')->get();
        $filename = 'laporan-penjualan-marketing-'.now()->format('Ymd-His').'.xls';

        return response($this->buildExcel($sales), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $sales = $this->filteredSalesQuery($request)->with('marketing')->latest('deal_date')->get();
        $filename = 'laporan-penjualan-marketing-'.now()->format('Ymd-His').'.pdf';

        return response($this->buildPdf($sales), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function filteredSalesQuery(Request $request, ?string $defaultMonth = null)
    {
        $month = $this->validMonth($request->input('month')) ?: $defaultMonth;

        return MarketingSale::query()
            ->when($month, fn ($query) => $query->whereYear('deal_date', substr($month, 0, 4))->whereMonth('deal_date', substr($month, 5, 2)))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->user_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status));
    }

    private function marketingUsers()
    {
        return User::where('role', 'marketing')->orderBy('name')->get(['id', 'name', 'email', 'phone', 'profile_photo', 'is_active']);
    }

    private function targetAmountForPeriod(Request $request, string $month): int
    {
        return (int) MarketingTarget::query()
            ->where('target_type', 'monthly')
            ->where('period_year', (int) substr($month, 0, 4))
            ->where('period_month', (int) substr($month, 5, 2))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->user_id))
            ->sum('target_amount');
    }

    private function validateTarget(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'marketing'))],
            'target_type' => ['required', Rule::in(array_keys(MarketingTarget::TYPES))],
            'period_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'period_month' => ['nullable', 'integer', 'between:1,12', Rule::requiredIf(fn () => $request->target_type === 'monthly')],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function validMonth(?string $month): ?string
    {
        return is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) ? $month : null;
    }

    private function buildExcel($sales): string
    {
        $headers = ['Tanggal Deal', 'Marketing', 'Kategori', 'Produk', 'Customer', 'Harga Deal', 'Status', 'Persen Komisi', 'Komisi Fix', 'Total Komisi', 'Catatan'];
        $html = '<html><head><meta charset="UTF-8"></head><body><h2>Laporan Penjualan Marketing</h2><table border="1"><thead><tr>';

        foreach ($headers as $header) {
            $html .= '<th style="background:#2563eb;color:#ffffff;font-weight:bold;">'.e($header).'</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($sales as $sale) {
            $html .= '<tr>';
            foreach ([
                optional($sale->deal_date)->format('d/m/Y'),
                $sale->marketing->name ?? '-',
                $sale->categoryLabel(),
                $sale->product_name,
                $sale->customer_name,
                $sale->deal_price,
                MarketingSale::STATUSES[$sale->status] ?? $sale->status,
                $sale->commission_percent,
                $sale->commission_fixed,
                $sale->commission_total,
                $sale->notes,
            ] as $value) {
                $html .= '<td>'.e((string) $value).'</td>';
            }
            $html .= '</tr>';
        }

        return "\xEF\xBB\xBF".$html.'</tbody></table></body></html>';
    }

    private function buildPdf($sales): string
    {
        $lines = [
            'Laporan Penjualan Marketing',
            'Dicetak: '.now()->format('d/m/Y H:i'),
            'Total data: '.$sales->count(),
            '',
        ];

        foreach ($sales as $index => $sale) {
            $lines[] = ($index + 1).'. '.$sale->product_name.' - '.$sale->marketing?->name;
            $lines[] = '   '.$sale->categoryLabel().' | '.MarketingSale::STATUSES[$sale->status].' | Rp '.number_format($sale->deal_price, 0, ',', '.');
            $lines[] = '   Komisi: Rp '.number_format($sale->commission_total, 0, ',', '.').' | Customer: '.$sale->customer_name;
            $lines[] = '';
        }

        return $this->makeTextPdf($lines);
    }

    private function makeTextPdf(array $lines): string
    {
        $content = "BT\n/F1 9 Tf\n";
        foreach ($lines as $index => $line) {
            $content .= '1 0 0 1 36 '.(560 - ($index * 13)).' Tm ('.$this->pdfEscape($line).") Tj\n";
        }
        $content .= 'ET';

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            "<< /Length ".strlen($content)." >>\nstream\n{$content}\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        return $pdf."trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";
    }

    private function pdfEscape(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^\x20-\x7E]/', '', $text);

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], mb_strimwidth($text, 0, 130, '...'));
    }
}
