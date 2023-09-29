<?php

namespace app\models\search;

use app\entities\CashBack;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CashBackSearch extends Model
{

    public function search($params): ActiveDataProvider
    {
        $this->load($params) && $this->validate();

        $finance = CashBack::find();

        return new ActiveDataProvider([
            'query' => $finance,
        ]);
    }

    public function getRangeList(string $attribute = null): array
    {
        return $this->_filters[$attribute] ?? [];
    }
}