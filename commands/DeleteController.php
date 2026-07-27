<?php

namespace app\commands;

use app\services\DeleteService;
use Exception;
use Throwable;
use yii\console\Controller;

class DeleteController extends Controller
{
    private DeleteService $service;

    public function __construct(string        $id,
                                              $module,
                                DeleteService $service,
                                array         $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function actionDeleteTBank(string $date): void
    {
        if (!$date) throw new Exception('Необходимо ввести дату');

        $count = $this->service->deleteTBankByDate($date);

        $this->stdout('Удалено: ' . $count . PHP_EOL);
    }
}