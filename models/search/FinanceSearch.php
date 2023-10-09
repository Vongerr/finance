<?php

namespace app\models\search;

use app\entities\CashBack;
use app\entities\Finance;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class FinanceSearch extends Model
{
    public $category;

    public $budget_category;

    public $date;

    private array $_filters = [];

    public function rules(): array
    {
        return [
            [['category', 'budget_category', 'date'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $finance = Finance::find();

        if ($this->load($params) && $this->validate()) {
            $finance
                ->andFilterWhere(['date' => $this->date ? date('Y-m-d', strtotime($this->date)) : null])
                ->andFilterWhere(['category' => $this->category])
                ->andFilterWhere(['budget_category' => $this->budget_category]);
        }

        $this->_filters = [
            'category' => $this->defineFilterCategory(),
            'budget_category' => $this->defineFilterCategoryBudget(),
        ];

        return new ActiveDataProvider([
            'query' => $finance,
        ]);
    }

    private function filterQuery(): ActiveQuery
    {
        return Finance::find()
            ->asArray();
    }

    private function defineFilterCategoryBudget(): array
    {
        $filter = $this->filterQuery()
            ->select(['budget_category'])
            ->column();

        $list = [];

        foreach ($filter as $attribute) {
            $list[$attribute] = CategoryBudgetHelper::getValue($attribute);
        }

        return $list;
    }

    private function defineFilterCategory(): array
    {
        $filter = $this->filterQuery()
            ->select(['category'])
            ->column();

        $list = [];

        foreach ($filter as $attribute) {
            $list[$attribute] = CategoryHelper::getValue($attribute);
        }

        return $list;
    }

    public function getFinanceInfo(): array
    {
        $info = [];

        $cashBackCategoryList = $this->buildCashBackCategoryList();

        foreach ($this->queryFinance() as $item) {
            $month = date('n', strtotime($item->date));

            if (!isset($info[$month])) {
                $info[$month] = [
                    'finance' => 0,
                    'cashback' => 0
                ];
            }

            if (Finance::EXPENSES == $item->budget_category) {

                $info[$month]['finance'] -= $item->money;

                $info[$month]['cashback'] += $this->defineCashBack($item, $cashBackCategoryList);
            } else {

                $info[$month]['finance'] += $item->money;
            }
        }

        foreach ($info as $index => $monthInfo) {

            $info[$index]['finance'] += $monthInfo['cashback'];
        }

        return $info;
    }

    /**
     * @return array|CashBack[]
     */
    private function buildCashBackCategoryList(): array
    {
        $cashBackCategoryList = [];

        foreach (CashBack::find()->all() as $category) {
            $index = $category->year . $category->month . $category->category;

            $cashBackCategoryList[$index] = $category;
        }

        return $cashBackCategoryList;
    }

    private function defineCashBack(Finance $item, array $cashBackCategoryList): float|int
    {
        if ($item->category != Finance::TRANSFER) {

            $index = date('Y') . date('n') . $item->category;

            $cashBackCategory = $cashBackCategoryList[$index] ?? null;

            $cashBack = 0.01;

            if ($cashBackCategory) {
                $cashBack = $cashBackCategory->individual_category != null
                    ? ($cashBackCategory->individual_category == $item->comment ? $cashBackCategory->percent / 100 : $cashBack)
                    : $cashBackCategory->percent / 100;
            }

            return floor($item->money * $cashBack);
        }

        return 0;
    }

    /**
     * @return array|Finance[]
     */
    private function queryFinance(): array
    {
        return Finance::find()->all();
    }

    public function getRangeList(string $attribute = null): array
    {
        return $this->_filters[$attribute] ?? [];
    }
}