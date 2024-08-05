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

        $scholarshipModels = Finance::find()
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

        $scholarshipData = [];

        foreach ($scholarshipModels as $scholarshipModel) {
            $scholarshipData[date('Y', strtotime($scholarshipModel->date))][date('m', strtotime($scholarshipModel->date))]
                = $scholarshipModel->money;
        }

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

            $money = $finance->money;

            if (Finance::EXPENSES == $finance->budget_category) {

                $data[$year][$month]['expenses'] += $money;
                $data[$year][$month]['finance'] -= $money;
            } else {

                if (Finance::SALARY == $finance->category)
                    $data[$year][$month]['salary'] += $money;

                if (Finance::SCHOLARSHIP == $finance->category) {

                    $scholarship = $money;

                    if ($money > 6000)
                        $scholarship -= $scholarshipData[date('Y', strtotime($finance->date))][date('m', strtotime($finance->date))] ?? 0;

                    $data[$year][$month]['scholarship'] += $scholarship;
                }

                $data[$year][$month]['revenue'] += $money;
                $data[$year][$month]['finance'] += $money;
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