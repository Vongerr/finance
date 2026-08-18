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

    private const BAR_GREEN = '#2e8b57';
    private const BAR_ORANGE = '#e67e22';
    private const BAR_RED = '#c0392b';

    private DayStatisticRepository $repository;

    public function __construct(DayStatisticRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function formatMoney(float $value): string
    {
        return number_format($value, 0, ',', '.');
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
                'has_data' => false,
                'is_weekend' => false,
                'is_max_net' => false,
                'is_max_exp' => false,
                'bar_percent' => 0,
                'bar_color' => self::BAR_GREEN,
                'avg_op' => 0.0,
                'top_label' => null,
                'top_value' => null,
            ];
        }

        foreach ($this->repository->getDayTotals($year, $month) as $row) {
            $date = $row['date'];
            if (!isset($days[$date])) {
                continue;
            }
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
            if (!isset($days[$date])) {
                continue;
            }
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
            $info['has_data'] = $info['count'] > 0;
            $info['is_weekend'] = in_array($info['weekday'], ['Сб', 'Вс'], true);
            $info['avg_op'] = $info['expenses'] > 0 && $info['count'] > 0 ? $info['expenses'] / $info['count'] : 0.0;

            arsort($info['rows']);
            $info['rows'] = array_slice($info['rows'], 0, 3, true);
            if ($info['rows']) {
                $topKeys = array_keys($info['rows']);
                $info['top_label'] = $topKeys[0];
                $info['top_value'] = $info['rows'][$topKeys[0]];
            }

            $totalRevenue += $info['revenue'];
            $totalExpenses += $info['expenses'];

            if ($info['has_data']) {
                $activeDays++;
            }
            if ($info['expenses'] > $maxDayExpense) {
                $maxDayExpense = $info['expenses'];
                $maxExpenseDay = $date;
            }
            if ($info['net'] >= 0 && ($maxNetDay === null || $info['net'] > $days[$maxNetDay]['net'])) {
                $maxNetDay = $date;
            }
        }
        unset($info);

        $maxDayExpenseSafe = max($maxDayExpense, 1);

        foreach ($days as $date => &$info) {
            $info['is_max_net'] = $info['net'] > 0 && $date === $maxNetDay;
            $info['is_max_exp'] = $maxDayExpense > 0 && $date === $maxExpenseDay;
            $info['bar_percent'] = $info['expenses'] > 0 ? (int)round($info['expenses'] / $maxDayExpenseSafe * 100) : 0;
            $info['bar_color'] = $info['bar_percent'] >= 80
                ? self::BAR_RED
                : ($info['bar_percent'] >= 45 ? self::BAR_ORANGE : self::BAR_GREEN);
        }
        unset($info);

        $weeks = [];
        $currentWeek = null;
        $week = null;

        foreach ($days as $info) {
            $weekNumber = (int)date('W', strtotime($info['date']));
            if ($currentWeek !== $weekNumber) {
                $currentWeek = $weekNumber;
                $week = [
                    'number' => $weekNumber,
                    'first' => $info['date'],
                    'last' => $info['date'],
                    'revenue' => 0.0,
                    'expenses' => 0.0,
                    'net' => 0.0,
                    'days' => [],
                ];
                $weeks[] = &$week;
            }
            $week['last'] = $info['date'];
            $week['revenue'] += $info['revenue'];
            $week['expenses'] += $info['expenses'];
            $week['net'] += $info['net'];
            $week['days'][] = $info;
        }
        unset($week);

        $chartLabels = [];
        $chartExpenses = [];
        $chartRevenue = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $chartLabels[] = $day;
            $chartExpenses[] = round($days[$date]['expenses'], 2);
            $chartRevenue[] = round($days[$date]['revenue'], 2);
        }

        return [
            'year' => $year,
            'month' => $month,
            'monthName' => $daysInMonth ? $this->getMonthName($month) : '',
            'daysInMonth' => $daysInMonth,
            'weeks' => $weeks,
            'chart' => [
                'labels' => $chartLabels,
                'expenses' => $chartExpenses,
                'revenue' => $chartRevenue,
            ],
            'summary' => [
                'totalRevenue' => $totalRevenue,
                'totalExpenses' => $totalExpenses,
                'totalNet' => $totalRevenue - $totalExpenses,
                'activeDays' => $activeDays,
                'avgDaily' => $activeDays > 0 ? $totalExpenses / $activeDays : 0,
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