<?php

/**
 * Get array of month list.
 *
 * @return array
 */
function get_months()
{
    return [
        '01' => __('time.months.01'),
        '02' => __('time.months.02'),
        '03' => __('time.months.03'),
        '04' => __('time.months.04'),
        '05' => __('time.months.05'),
        '06' => __('time.months.06'),
        '07' => __('time.months.07'),
        '08' => __('time.months.08'),
        '09' => __('time.months.09'),
        '10' => __('time.months.10'),
        '11' => __('time.months.11'),
        '12' => __('time.months.12'),
    ];
}

/**
 * Get array of year list starting from 2019.
 *
 * @return array
 */
function get_years()
{
    $yearRange = range(2019, date('Y'));
    foreach ($yearRange as $year) {
        $years[$year] = $year;
    }

    return $years;
}
