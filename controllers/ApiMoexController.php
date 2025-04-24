<?php

namespace app\controllers;

use app\services\MoexService;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

class ApiMoexController extends MainController
{
    private MoexService $service;

    public function __construct($id, $module, MoexService $service, $config = [])
    {
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $this->service->moexInfo();
    }
}