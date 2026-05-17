<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class UserActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Analisis Jumlah Pengunjung';

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'daily';

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'total' => 'Total Pengunjung',
        ];
    }

    protected function getData(): array
    {
        $chart = match ($this->filter) {
            'weekly' => $this->getWeeklyChartData(),
            'monthly' => $this->getMonthlyChartData(),
            'total' => $this->getCumulativeChartData(),
            default => $this->getDailyChartData(),
        };

        return [
            'datasets' => [
                [
                    'label' => $chart['label'],
                    'data' => $chart['data'],
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.14)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getDailyChartData(): array
    {
        $days = 7;
        $start = Carbon::today()->subDays($days - 1);
        $visits = $this->getVisitsFrom($start);
        $visitorsByDate = [];

        foreach ($visits as $visit) {
            $date = $visit->visited_at->toDateString();
            $visitorsByDate[$date][$this->visitorKey($visit)] = true;
        }

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();

            $labels[] = $date->format('d M');
            $data[] = count($visitorsByDate[$key] ?? []);
        }

        return [
            'label' => 'Pengunjung harian',
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function getWeeklyChartData(): array
    {
        $weeks = 12;
        $start = Carbon::today()->startOfWeek()->subWeeks($weeks - 1);
        $visits = $this->getVisitsFrom($start);
        $visitorsByWeek = [];

        foreach ($visits as $visit) {
            $week = $visit->visited_at->copy()->startOfWeek()->toDateString();
            $visitorsByWeek[$week][$this->visitorKey($visit)] = true;
        }

        $labels = [];
        $data = [];

        for ($i = 0; $i < $weeks; $i++) {
            $weekStart = $start->copy()->addWeeks($i);
            $key = $weekStart->toDateString();

            $labels[] = $weekStart->format('d M');
            $data[] = count($visitorsByWeek[$key] ?? []);
        }

        return [
            'label' => 'Pengunjung mingguan',
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function getMonthlyChartData(): array
    {
        $months = 12;
        $start = Carbon::today()->startOfMonth()->subMonths($months - 1);
        $visits = $this->getVisitsFrom($start);
        $visitorsByMonth = [];

        foreach ($visits as $visit) {
            $month = $visit->visited_at->format('Y-m');
            $visitorsByMonth[$month][$this->visitorKey($visit)] = true;
        }

        $labels = [];
        $data = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');

            $labels[] = $month->format('M Y');
            $data[] = count($visitorsByMonth[$key] ?? []);
        }

        return [
            'label' => 'Pengunjung bulanan',
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function getCumulativeChartData(): array
    {
        $days = 30;
        $start = Carbon::today()->subDays($days - 1);
        $end = Carbon::today()->endOfDay();
        $visits = SiteVisit::query()
            ->where('visited_at', '<=', $end)
            ->orderBy('visited_at')
            ->get(['id', 'session_id', 'ip_address', 'visited_at']);

        $seenVisitors = [];
        $visitorsByDate = [];

        foreach ($visits as $visit) {
            $visitorKey = $this->visitorKey($visit);

            if ($visit->visited_at->lt($start)) {
                $seenVisitors[$visitorKey] = true;
                continue;
            }

            $date = $visit->visited_at->toDateString();
            $visitorsByDate[$date][] = $visitorKey;
        }

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();

            foreach ($visitorsByDate[$key] ?? [] as $visitorKey) {
                $seenVisitors[$visitorKey] = true;
            }

            $labels[] = $date->format('d M');
            $data[] = count($seenVisitors);
        }

        return [
            'label' => 'Total pengunjung kumulatif',
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function getVisitsFrom(Carbon $start): Collection
    {
        return SiteVisit::query()
            ->where('visited_at', '>=', $start)
            ->orderBy('visited_at')
            ->get(['id', 'session_id', 'ip_address', 'visited_at']);
    }

    protected function visitorKey(SiteVisit $visit): string
    {
        return (string) ($visit->session_id ?: $visit->ip_address ?: $visit->id);
    }
}
