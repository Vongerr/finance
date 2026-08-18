<?php

namespace app\modules\DayStatistic\repositories;

use app\entities\Finance;
use yii\db\ActiveQuery;

class DayStatisticRepository
{
    private function queryFinance(int $year, int $month): ActiveQuery
    {
        return Finance::find()
            ->andWhere(['YEAR(date)' => $year, 'MONTH(date)' => $month])
            ->andWhere(['exclusion' => Finance::NO_EXCLUSION])
            ->asArray();
    }

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
        return $this->queryFinance($year, $month)
            ->select([
                'date',
                'budget_category',
                'SUM(money) as total',
                'COUNT(*) as count',
            ])
            ->groupBy(['date', 'budget_category'])
            ->all();
    }

    /**
     * Разбивка расходов по категориям внутри каждого дня.
     *
     * @return array|Finance[]
     */
    public function getDayCategoryBreakdown(int $year, int $month): array
    {
        return $this->queryFinance($year, $month)
            ->andWhere(['budget_category' => Finance::EXPENSES])
            ->select([
                'date',
                'category',
                'SUM(money) as total',
                'COUNT(*) as count',
            ])
            ->groupBy(['date', 'category'])
            ->all();
    }
}