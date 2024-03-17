<?php

namespace app\repositories;

use app\entities\Finance;
use app\forms\FinanceForm;
use DomainException;
use Exception;
use RuntimeException;
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

    public function getHashFinance(FinanceForm $form): string
    {
        return md5(
            $form->budget_category
            . $form->category
            . $form->date
            . $form->time
            . $form->money
        );
    }

    public function save(Finance $model): bool
    {
        try {
            if (!$model->validate()) {

                if (YII_DEBUG) {

                    printr($model->getErrors(), 1);
                } else {

                    throw new RuntimeException($model->getErrors());
                }
            }

            return $model->save();

        } catch (Exception $e) {

            throw new RuntimeException($e->getMessage());
        }
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