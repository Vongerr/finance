<?php

namespace app\services;

use app\entities\Finance;
use app\repositories\DeleteRepository;
use Throwable;
use yii\db\StaleObjectException;

class DeleteService
{
    private DeleteRepository $repository;

    public function __construct(DeleteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteBank(string $bank): void
    {
        $count = 0;

        foreach ($this->repository->findFinanceByBank($bank) as $finance) {

            $finance->delete();

            ++$count;
        }

        echo 'Успешно удалено: ' . $count;
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteTBankByDate(string $date): int
    {
        $count = 0;

        foreach ($this->repository->findByDate($date, Finance::TINKOFF) as $finance) {

            $finance->delete();

            ++$count;
        }

        return $count;
    }
}