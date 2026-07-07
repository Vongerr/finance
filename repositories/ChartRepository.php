<?php

namespace app\repositories;

use app\entities\Finance;

class ChartRepository
{
    public function getCategoryExpenses(?string $year, ?string $month, ?string $category): array
    {
        return Finance::find()
            ->andWhere(['budget_category' => Finance::EXPENSES])
            ->andWhere(['YEAR(date)' => $year ?: date('Y')])
            ->andFilterWhere(['MONTH(date)' => $month])
            ->andFilterWhere(['category' => $category])
            ->select(['category', 'SUM(money) as total', 'COUNT(*) as count'])
            ->groupBy('category')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()
            ->all();
    }

    public function getAvailableYears(): array
    {
        return Finance::find()
            ->select(['YEAR(date) as year'])
            ->orderBy(['year' => SORT_DESC])
            ->distinct()
            ->column();
    }
}
