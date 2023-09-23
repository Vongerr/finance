<?php

namespace app\repositories;

use app\entities\Finance;
use DomainException;
use Throwable;
use yii\db\StaleObjectException;

class FinanceRepository
{
    public function getStatisticById($id): Finance
    {
        return Finance::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
    }

    /**
     * @throws Throwable
     */
    public function remove(Finance $ads)
    {
        try {

            $ads->delete();

        } catch (StaleObjectException $e) {
            throw new DomainException('Error deleted!');
        }
    }
}