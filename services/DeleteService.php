<?php

namespace app\services;

use app\entities\Finance;
use Throwable;
use yii\db\StaleObjectException;

class DeleteService
{
    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteBank(string $bank): void
    {
        $count = 0;

        foreach (Finance::find()->andWhere(['bank' => $bank])->all() as $finance) {

            $finance->delete();

            ++$count;
        }

        echo 'Успешно удалено: ' . $count;
    }
}