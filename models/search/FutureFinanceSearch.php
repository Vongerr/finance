<?php

namespace app\models\search;

use app\entities\Finance;
use app\helpers\BankHelper;
use app\helpers\CategoryAllHelper;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use app\helpers\ExclusionHelper;
use app\helpers\MonthHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class FutureFinanceSearch extends Model
{
    public $bank;

    public $category;

    public $budget_category;

    public $date;

    public $exclusion;

    public $comment;

    public $year;

    public $month;

    private array $_filters = [];

    public function rules(): array
    {
        return [
            [['exclusion', 'year', 'month'], 'integer'],
            [['category', 'budget_category', 'bank', 'date', 'comment'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $finance = Finance::find();

        if ($this->load($params) && $this->validate()) {

            $category = $this->category;

            if (Finance::OTHER == $this->category) $category = array_keys(array_diff(CategoryAllHelper::getList(), CategoryHelper::getList()));

            if (Finance::CASH . Finance::TRANSFER == $category) {

                $finance
                    ->andFilterWhere(['category' => [Finance::CASH, Finance::TRANSFER]]);

            } else {
                $finance
                    ->andFilterWhere(['category' => $category]);
            }

            $finance
                ->andFilterWhere(['date' => $this->date ? date('Y-m-d', strtotime($this->date)) : null])
                ->andFilterWhere(['bank' => $this->bank])
                ->andFilterWhere(['exclusion' => $this->exclusion])
                ->andFilterWhere(['like', 'comment', $this->comment])
                ->andFilterWhere(['budget_category' => $this->budget_category])
                ->andFilterWhere(['YEAR(date)' => $this->year])
                ->andFilterWhere(['MONTH(date)' => $this->month]);
        }

        if (!isset($params['sort'])) {
            $finance->orderBy(['date_time' => SORT_DESC]);
        }

        $this->_filters = [
            'exclusion' => ExclusionHelper::getList(),
            'bank' => $this->defineFilterBank(),
            'category' => CategoryHelper::getList(),
            'budget_category' => $this->defineFilterCategoryBudget(),
            'month' => $this->defineFilterMonth(),
            'year' => $this->defineFilterYear(),
        ];

        return new ActiveDataProvider([
            'query' => $finance,
        ]);
    }

    private function filterQuery(): ActiveQuery
    {
        return Finance::find()
            ->distinct()
            ->asArray();
    }

    private function defineFilterMonth(): array
    {
        $filter = $this->filterQuery()
            ->select(['MONTH(date) month'])
            ->column();

        $list = [];

        foreach ($filter as $attribute) {
            $list[$attribute] = MonthHelper::getValue($attribute);
        }

        return $list;
    }

    private function defineFilterYear(): array
    {
        $filter = $this->filterQuery()
            ->select(['YEAR(date) year'])
            ->column();

        $list = [];

        foreach ($filter as $attribute) {
            $list[$attribute] = $attribute;
        }

        return $list;
    }

    private function defineFilterBank(): array
    {
        $filter = $this->filterQuery()
            ->select(['bank'])
            ->column();

        $list = [];

        foreach ($filter as $attribute) {
            $list[$attribute] = BankHelper::getValue($attribute);
        }

        return $list;
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

    public function getRangeList(string $attribute = null): array
    {
        return $this->_filters[$attribute] ?? [];
    }
}