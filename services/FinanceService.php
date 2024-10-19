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

    public function defineCategoryFinance(): array
    {
        $models = $this->repository->getCategoryFinanceModels();

        $data = [];

        foreach ($models as $finance) {

            $month = date('n', strtotime($finance->date));
            $year = date('Y', strtotime($finance->date));

            if (!isset($data[$year][$month][$finance->category])) {

                $data[$year][$month][$finance->category] = 0;
            }

            if (Finance::TRANSFER == $finance->category && Finance::REVENUE == $finance->budget_category) {

                $data[$year][$month][$finance->category] -= $finance->money;
            } else {

                $data[$year][$month][$finance->category] += $finance->money;
            }
        }

        $categoryList = [];

        foreach ($data as $year => $yearInfoList) {

            foreach ($yearInfoList as $month => $monthInfo) {

                foreach ($monthInfo as $category => $value) {

                    if (!isset($categoryList[$year][$category])) $categoryList[$year][$category] = 0;

                    if (Finance::TRANSFER == $category && $value < 0) {

                        $data[$year][$month][$category] = 0;
                    } else {

                        $categoryList[$year][$category] += $value;
                    }
                }
            }
        }

        foreach ($categoryList as $year => $infoYear) {

            asort($categoryList[$year], SORT_ASC);
            $categoryList[$year] = array_reverse($categoryList[$year]);
        }

        ksort($categoryList);
        ksort($data);

        return ['data' => $data, 'categoryList' => $categoryList];
    }

    private function defineScholarship(): array
    {
        $scholarshipModels = $this->repository->getScholarshipInfo();

        $scholarshipData = [];

        foreach ($scholarshipModels as $scholarshipModel) {

            $time = strtotime($scholarshipModel->date);

            $scholarshipData[date('Y', $time)][date('m', $time)] = $scholarshipModel->money;
        }

        return $scholarshipData;
    }

    public function defineExpenses(): array
    {
        $models = $this->repository->getExpensesModels();

        $scholarshipData = $this->defineScholarship();

        $data = [];

        foreach ($models as $finance) {


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

                    if ('2023-06' == date('Y-m', strtotime($finance->date))) $scholarship -= 13500;

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