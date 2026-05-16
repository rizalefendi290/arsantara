<?php

namespace App\Services;

use App\Models\SalesCommissionRule;

class SalesCommissionCalculator
{
    public function calculate(string $category, int $dealPrice): array
    {
        $rule = SalesCommissionRule::active()->where('category', $category)->first();

        $percent = (float) ($rule?->commission_percent ?? 0);
        $fixed = (int) ($rule?->commission_fixed ?? 0);
        $total = (int) round(($dealPrice * $percent / 100) + $fixed);

        return [
            'commission_percent' => $percent,
            'commission_fixed' => $fixed,
            'commission_total' => $total,
        ];
    }
}
