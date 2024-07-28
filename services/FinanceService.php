<?php

namespace app\services;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\repositories\FinanceRepository;
use Throwable;
use yii\db\Exception;

class FinanceService
{
    private FinanceRepository $repository;

    public function __construct(FinanceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStatisticById(int $id): Finance
    {
        return $this->repository->getStatisticById($id);
    }

    public function defineExpenses(): array
    {
        $models = $this->repository->getExpensesModels();

        $data = [];

        foreach ($models as $finance) {

            if ($finance->exclusion == Finance::EXCLUSION) continue;

            $month = date('n', strtotime($finance->date));
            $year = date('Y', strtotime($finance->date));

            if (!isset($data[$year][$month])) {
                $data[$year][$month] = [
                    'finance' => 0,
                    'expenses' => 0,
                    'revenue' => 0,
                    'salary' => 0,
                    'scholarship' => 0,
                ];
            }

            if (Finance::EXPENSES == $finance->budget_category) {

                $data[$year][$month]['expenses'] += $finance->money;
                $data[$year][$month]['finance'] -= $finance->money;
            } else {

                if (Finance::SALARY == $finance->category)
                    $data[$year][$month]['salary'] += $finance->money;

                if (Finance::SCHOLARSHIP == $finance->category) {

                    $data[$year][$month]['scholarship'] += $finance->money;
                    //$data[$year][$month]['scholarship'] -=
                }

                $data[$year][$month]['finance'] += $finance->money;
                $data[$year][$month]['revenue'] += $finance->money;
            }
        }

        foreach ($data as $indexYear => $yearInfo) {

            ksort($data[$indexYear]);
        }

        ksort($data);

        return $data;
    }

    /**
     * @throws Exception
     */
    public function create(FinanceForm $form): void
    {
        $hashList = [];

        foreach (Finance::find()->asArray()->select(['hash'])->column() as $item) {
            $hashList[$item] = $item;
        }

        $model = Finance::create($form);

        if (isset($hashList[$model->hash])) {
            throw new Exception('Такая запись уже существует');
        }

        $this->repository->save($model);
    }

    public function update(FinanceForm $form, Finance $model): void
    {
        $model->edit($form);

        $this->repository->save($model);
    }

    /**
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $model = $this->repository->getStatisticById($id);

        $this->repository->remove($model);
    }
}