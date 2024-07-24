<?php

namespace app\services;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\repositories\FinanceRepository;
use Throwable;
use yii\db\Exception;

class FinanceService
{
    /**
     * @var FinanceRepository
     */
    private $repository;

    public function __construct(FinanceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStatisticById(int $id): Finance
    {
        return $this->repository->getStatisticById($id);
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