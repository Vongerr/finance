<?php

namespace app\modules\DayStatistic\repositories;

use app\entities\Finance;

class DayStatisticRepository
{
    public function getAvailableYears(): array
    {
        return Finance::find()
            ->select(['YEAR(date) as year'])
            ->orderBy(['year' => SORT_DESC])
            ->distinct()
            ->column();
    }

    /**
     * Итоги по дням за выбранный месяц, сгруппированные по типу бюджета.
     *
     * @return array|Finance[]
     */
    public function getDayTotals(int $year, int $month): array
    {
        return Finance::find()
            ->andWhere(['YEAR(date)' => $year, 'MONTH(date)' => $month])
            ->andWhere(['exclusion' => Finance::NO_EXCLUSION])
            ->select([
                'DATE_FORMAT(date, "%Y-%m-%d") as day',
                'budget_category',
                'SUM(money) as total',
                'COUNT(*) as count',
            ])
            ->groupBy(['day', 'budget_category'])
            ->asArray()
            ->all();
    }

    /**
     * Разбивка расходов по категориям внутри каждого дня.
     *
     * @return array|Finance[]
     */
    public function getDayCategoryBreakdown(int $year, int $month): array
    {
        return Finance::find()
            ->andWhere(['YEAR(date)' => $year, 'MONTH(date)' => $month])
            ->andWhere(['exclusion' => Finance::NO_EXCLUSION])
            ->andWhere(['budget_category' => Finance::EXPENSES])
            ->select([
                'DATE_FORMAT(date, "%Y-%m-%d") as day',
                'category',
                'SUM(money) as total',
                'COUNT(*) as count',
            ])
            ->groupBy(['day', 'category'])
            ->asArray()
            ->all();
    }
}