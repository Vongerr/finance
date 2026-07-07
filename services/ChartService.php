<?php

namespace app\services;

use app\helpers\CategoryAllHelper;
use app\repositories\ChartRepository;

class ChartService
{
    private const PALETTE = [
        '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc',
        '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#17a2b8',
        '#6610f2', '#28a745', '#dc3545', '#ffc107', '#007bff',
        '#6c757d', '#343a40', '#f8f9fa', '#7952b3', '#198754',
    ];

    private ChartRepository $repository;

    public function __construct(ChartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategoryExpenses(?string $year, ?string $month, ?string $category): array
    {
        return $this->repository->getCategoryExpenses($year, $month, $category);
    }

    public function getAvailableYears(): array
    {
        return $this->repository->getAvailableYears();
    }

    public function buildChartData(array $expenses): array
    {
        $categories = CategoryAllHelper::getList();
        $totalSum = array_sum(array_column($expenses, 'total'));

        $labels = [];
        $values = [];
        $colors = [];
        $rows = [];

        foreach ($expenses as $i => $row) {
            $catName = $categories[$row['category']] ?? $row['category'];
            $percent = $totalSum > 0 ? round($row['total'] / $totalSum * 100, 1) : 0;

            $labels[] = $catName;
            $values[] = round($row['total'], 2);
            $colors[] = self::PALETTE[$i % count(self::PALETTE)];

            $rows[] = [
                'category' => $catName,
                'category_key' => $row['category'],
                'total' => $row['total'],
                'count' => $row['count'],
                'percent' => $percent,
            ];
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
            'rows' => $rows,
            'totalSum' => $totalSum,
        ];
    }
}
