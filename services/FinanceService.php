<?php

namespace app\services;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\repositories\FinanceRepository;
use Throwable;

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

    public function create(FinanceForm $form)
    {
        $model = Finance::create($form);

        $model->save();
    }

    public function update(FinanceForm $form, Finance $model)
    {
        $model->edit($form);

        $model->save();
    }

    /**
     * @throws Throwable
     */
    public function remove(int $id)
    {
        $model = $this->repository->getStatisticById($id);

        $this->repository->remove($model);
    }
}