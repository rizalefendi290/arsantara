<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pengunjung Hari Ini', number_format($this->countUniqueVisitors(Carbon::today())))
                ->description('Jumlah pengunjung unik hari ini.')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart($this->getDailyUniqueVisitorChartData(7))
                ->chartColor('success')
                ->color('success'),

            Stat::make('Pengunjung Minggu Ini', number_format($this->countUniqueVisitors(Carbon::now()->startOfWeek())))
                ->description('Jumlah pengunjung unik minggu berjalan.')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart($this->getDailyUniqueVisitorChartData(7))
                ->chartColor('info')
                ->color('info'),

            Stat::make('Pengunjung Bulan Ini', number_format($this->countUniqueVisitors(Carbon::now()->startOfMonth())))
                ->description('Jumlah pengunjung unik bulan berjalan.')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->chart($this->getDailyUniqueVisitorChartData(30))
                ->chartColor('warning')
                ->color('warning'),

            Stat::make('Total Pengunjung', number_format($this->countUniqueVisitors()))
                ->description('Akumulasi seluruh pengunjung unik.')
                ->descriptionIcon('heroicon-m-users')
                ->chart($this->getMonthlyUniqueVisitorChartData(12))
                ->chartColor('primary')
                ->color('primary'),
        ];
    }

    protected function countUniqueVisitors(?Carbon $startDate = null): int
    {
        return (int) SiteVisit::query()
            ->when($startDate, fn ($query) => $query->where('visited_at', '>=', $startDate))
            ->selectRaw("COUNT(DISTINCT {$this->uniqueVisitorExpression()}) as total")
            ->value('total');
    }

    /**
     * @return array<int, int>
     */
    protected function getDailyUniqueVisitorChartData(int $days): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $visitors = SiteVisit::query()
            ->selectRaw("DATE(visited_at) as visit_date, COUNT(DISTINCT {$this->uniqueVisitorExpression()}) as total")
            ->where('visited_at', '>=', $start)
            ->groupByRaw('DATE(visited_at)')
            ->orderBy('visit_date')
            ->pluck('total', 'visit_date')
            ->toArray();

        $chart = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $chart[] = (int) ($visitors[$date->toDateString()] ?? 0);
        }

        return $chart;
    }

    /**
     * @return array<int, int>
     */
    protected function getMonthlyUniqueVisitorChartData(int $months): array
    {
        $start = Carbon::today()->startOfMonth()->subMonths($months - 1);

        $visits = SiteVisit::query()
            ->where('visited_at', '>=', $start)
            ->get(['id', 'session_id', 'ip_address', 'visited_at']);

        $visitorsByMonth = [];

        foreach ($visits as $visit) {
            $month = $visit->visited_at->format('Y-m');
            $visitorsByMonth[$month][$this->visitorKey($visit)] = true;
        }

        $chart = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i)->format('Y-m');
            $chart[] = count($visitorsByMonth[$month] ?? []);
        }

        return $chart;
    }

    protected function visitorKey(SiteVisit $visit): string
    {
        return (string) ($visit->session_id ?: $visit->ip_address ?: $visit->id);
    }

    protected function uniqueVisitorExpression(): string
    {
        return match (SiteVisit::query()->getConnection()->getDriverName()) {
            'pgsql' => 'COALESCE(session_id, ip_address, id::text)',
            'sqlite' => 'COALESCE(session_id, ip_address, CAST(id AS TEXT))',
            default => 'COALESCE(session_id, ip_address, CAST(id AS CHAR))',
        };
    }
}
