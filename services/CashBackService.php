<?php

namespace app\services;

use app\entities\CashBack;
use app\forms\CashBackForm;
use app\repositories\CashBackRepository;
use Throwable;

class CashBackService
{
    /**
     * @var CashBackRepository
     */
    private $repository;

    public function __construct(CashBackRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStatisticById(int $id): CashBack
    {
        return $this->repository->getStatisticById($id);
    }

    public function create(CashBackForm $form)
    {
        $model = CashBack::create($form);

        $model->save();
    }

    public function update(CashBackForm $form, CashBack $model)
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