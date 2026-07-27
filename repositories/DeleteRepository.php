<?php

namespace app\repositories;

use app\entities\Finance;
use yii\db\ActiveQuery;

class DeleteRepository
{
    private function queryFinanceByBank(string $bank): ActiveQuery
    {
        return Finance::find()->andWhere(['bank' => $bank]);
    }

    public function findFinanceByBank(string $bank): array
    {
        return $this->queryFinanceByBank($bank)->all();
    }

    public function findByDate(string $date, string $bank): array
    {
        return $this->queryFinanceByBank($bank)->andWhere(['>', 'created_at', $date])->all();
    }
}