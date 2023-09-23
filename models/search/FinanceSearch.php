<?php

namespace app\models\search;

use app\entities\Finance;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FinanceSearch extends Model
{
    public $category;

    public $category_budget;

    public $date;

    public function rules(): array
    {
        return [
            [['category', 'category_budget', 'date'], 'string']
        ];
    }

    public function search(): ActiveDataProvider
    {
        $finance = Finance::find();

        return new ActiveDataProvider([
            'query' => $finance,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);
    }

    public function getFinanceInfo()
    {
        $info = 0;

        foreach (Finance::find()->all() as $item) {
            $info = $item->budget_category == Finance::EXPENSES
                ? $info - $item->money
                : $info + $item->money;
        }

        return $info;
    }

    /**
     * @param string|null $attribute
     * @return array
     */
    public function getRangeList(string $attribute = null): array
    {
        switch ($attribute) {

            case 'category':

                return $this->category ?? [];

            default:
                return [];
        }
    }
}