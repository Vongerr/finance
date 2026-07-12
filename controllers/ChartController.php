<?php

namespace app\controllers;

use app\helpers\CategoryAllHelper;
use app\services\ChartService;

class ChartController extends MainController
{
    protected const CONTROLLER_TITLE = 'Диаграмма по категориям';

    private ChartService $chartService;

    public function __construct($id, $module, ChartService $chartService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->chartService = $chartService;
    }

    public function actionIndex(): string
    {
        $request = app()->request;
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $category = $request->get('category');

        $expenses = $this->chartService->getCategoryExpenses($year, $month, $category);
        $chartData = $this->chartService->buildChartData($expenses);
        $years = $this->chartService->getAvailableYears();

        return $this->render('index', [
            'chartData' => $chartData,
            'years' => $years,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'categories' => CategoryAllHelper::getList(),
            'selectedCategory' => $category,
        ]);
    }
}
