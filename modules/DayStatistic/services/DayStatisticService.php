<?php

namespace app\modules\DayStatistic\services;

use app\entities\Finance;
use app\helpers\CategoryAllHelper;
use app\modules\DayStatistic\repositories\DayStatisticRepository;

class DayStatisticService
{
    private const WEEKDAYS = [
        1 => 'Пн',
        2 => 'Вт',
        3 => 'Ср',
        4 => 'Чт',
        5 => 'Пт',
        6 => 'Сб',
        7 => 'Вс',
    ];

    private DayStatisticRepository $repository;

    public function __construct(DayStatisticRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAvailableYears(): array
    {
        return $this->repository->getAvailableYears();
    }

    public function buildMonthStatistic(int $year, int $month): array
    {
        $daysInMonth = (int)date('t', mktime(0, 0, 0, $month, 1, $year));

        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $days[$date] = [
                'date' => $date,
                'day' => $day,
                'weekday' => self::WEEKDAYS[(int)date('N', strtotime($date))] ?? '',
                'revenue' => 0.0,
                'expenses' => 0.0,
                'net' => 0.0,
                'count' => 0,
                'rows' => [],
            ];
        }

        foreach ($this->repository->getDayTotals($year, $month) as $row) {

            $date = $row['date'];

            if (!isset($days[$date])) continue;

            $days[$date]['count'] += (int)$row['count'];

            if ($row['budget_category'] === Finance::REVENUE) {
                $days[$date]['revenue'] += (float)$row['total'];
            } else {
                $days[$date]['expenses'] += (float)$row['total'];
            }
        }

        $categories = CategoryAllHelper::getList();

        foreach ($this->repository->getDayCategoryBreakdown($year, $month) as $row) {

            $date = $row['date'];

            if (!isset($days[$date])) continue;

            $label = $categories[$row['category']] ?? $row['category'];

            $days[$date]['rows'][$label] = ($days[$date]['rows'][$label] ?? 0) + (float)$row['total'];
        }

        $maxDayExpense = 0;
        $maxNetDay = null;
        $maxExpenseDay = null;
        $totalRevenue = 0.0;
        $totalExpenses = 0.0;
        $activeDays = 0;

        foreach ($days as $date => &$info) {

            $info['net'] = $info['revenue'] - $info['expenses'];

            arsort($info['rows']);

            $info['rows'] = array_slice($info['rows'], 0, 3, true);

            $totalRevenue += $info['revenue'];
            $totalExpenses += $info['expenses'];

            if ($info['count'] > 0) $activeDays++;

            if ($info['expenses'] > $maxDayExpense) {

                $maxDayExpense = $info['expenses'];
                $maxExpenseDay = $date;
            }

            if ($info['net'] >= 0 && ($maxNetDay === null || $info['net'] > $days[$maxNetDay]['net'])) {

                $maxNetDay = $date;
            }
        }

        unset($info);

        return [
            'days' => $days,
            'year' => $year,
            'month' => $month,
            'monthName' => $daysInMonth ? $this->getMonthName($month) : '',
            'daysInMonth' => $daysInMonth,
            'summary' => [
                'totalRevenue' => $totalRevenue,
                'totalExpenses' => $totalExpenses,
                'totalNet' => $totalRevenue - $totalExpenses,
                'activeDays' => $activeDays,
                'maxDayExpense' => $maxDayExpense,
                'maxExpenseDay' => $maxExpenseDay,
                'maxNetDay' => $maxNetDay,
            ],
        ];
    }

    private function getMonthName(int $month): string
    {
        return [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ][$month] ?? '';
    }
}