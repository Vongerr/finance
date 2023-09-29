<?php

namespace app\repositories;

use app\entities\CashBack;
use DomainException;
use Throwable;
use yii\db\StaleObjectException;

class CashBackRepository
{
    public function getStatisticById($id): CashBack
    {
        return CashBack::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
    }

    /**
     * @throws Throwable
     */
    public function remove(CashBack $ads)
    {
        try {
            $ads->delete();

        } catch (StaleObjectException $e) {
            throw new DomainException('Error deleted!');
        }
    }
}