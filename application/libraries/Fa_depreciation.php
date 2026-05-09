<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Straight-line depreciation helper for fixed assets.
 */
class Fa_depreciation
{
    /**
     * @param string|DateTimeInterface $purchase_date Y-m-d
     * @param string|DateTimeInterface|null $as_of_date Y-m-d (defaults to today)
     * @return array{annual_depreciation:float,accumulated_depreciation:float,current_book_value:float}
     */
    public function straight_line($purchase_cost, $salvage_value, $useful_life_years, $purchase_date, $as_of_date = null)
    {
        $purchase_cost = (float) $purchase_cost;
        $salvage_value = (float) $salvage_value;
        $life = (int) $useful_life_years;
        if ($life < 1) {
            $life = 1;
        }

        $depreciable = max(0, $purchase_cost - $salvage_value);
        $annual = $depreciable / $life;

        $as_of = $as_of_date ? new DateTime($as_of_date) : new DateTime('today');
        $purchase = new DateTime(is_string($purchase_date) ? $purchase_date : $purchase_date->format('Y-m-d'));

        if ($as_of < $purchase) {
            return array(
                'annual_depreciation' => round($annual, 2),
                'accumulated_depreciation' => 0.0,
                'current_book_value' => round($purchase_cost, 2),
            );
        }

        $interval = $purchase->diff($as_of);
        $years_elapsed = $interval->days / 365.25;

        $max_accum = $depreciable;
        $accumulated = min($max_accum, $annual * $years_elapsed);

        $book_raw = $purchase_cost - $accumulated;
        $book = max($salvage_value, $book_raw);

        return array(
            'annual_depreciation' => round($annual, 2),
            'accumulated_depreciation' => round($accumulated, 2),
            'current_book_value' => round($book, 2),
        );
    }
}
