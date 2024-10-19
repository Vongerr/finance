<?php

namespace app\repositories;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\helpers\CategoryAllHelper;
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

    /**
     * @return array|Finance[]
     */
    public function getScholarshipInfo(): array
    {
        return Finance::find()
            ->andWhere([
                'bank' => [Finance::OTKRITIE, Finance::VTB],
                'category' => Finance::TRANSFER,
                'exclusion' => Finance::NO_EXCLUSION,
                'budget_category' => Finance::EXPENSES,
            ])
            ->andWhere(['!=', 'comment', 'Ирина Ю.'])
            ->andWhere(['!=', 'comment', 'Даниил Д.'])
            ->andWhere(['!=', 'comment', 'Тхи Х.'])
            ->andWhere(['>', 'money', 6000])
            ->all();
    }

    /**
     * @return array|Finance[]
     */
    public function getExpensesModels(): array
    {
        return Finance::find()
            ->andWhere(['exclusion' => Finance::NO_EXCLUSION])
            ->all();
    }

    /**
     * @return array|Finance[]
     */
    public function getCategoryFinanceModels(): array
    {
        return Finance::find()
            ->andWhere(['exclusion' => Finance::NO_EXCLUSION])
            ->andWhere(['!=', 'category', Finance::SCHOLARSHIP])
            ->andWhere(['!=', 'category', Finance::SALARY])
            ->andWhere(['!=', 'category', Finance::CASH])
            ->all();
    }

    public function getFinanceList(): array
    {
        $hashList = [];

        $financeList = Finance::find()->all();

        foreach ($financeList as $modelOld) {
            $hashList[$modelOld->hash] = $modelOld->hash;
        }

        return $hashList;
    }

    public function getCategoryList(): array
    {
        $categories = [];

        foreach (CategoryAllHelper::getList() as $indexCategory => $item) {
            $categories[$item] = $indexCategory;
        }

        return $categories;
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
    public function remove(Finance $ads): void
    {
        try {

            $ads->delete();

        } catch (StaleObjectException $e) {
            throw new DomainException('Error deleted!');
        }
    }
}