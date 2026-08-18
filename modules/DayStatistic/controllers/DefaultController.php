<?php

namespace app\modules\DayStatistic\controllers;

use app\controllers\MainController;
use app\modules\DayStatistic\services\DayStatisticService;

class DefaultController extends MainController
{
    protected const CONTROLLER_TITLE = 'Статистика по дням';

    private DayStatisticService $service;

    public function __construct(string $id, $module, DayStatisticService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function actionIndex(): string
    {
        $request = app()->request;

        $year = (int)$request->get('year', date('Y'));
        $month = (int)$request->get('month', date('n'));

        return $this->render('index', [
            'statistic' => $this->service->buildMonthStatistic($year, $month),
            'years' => $this->service->getAvailableYears(),
        ]);
    }
}