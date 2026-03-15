<?php

namespace app\controllers;

use app\models\search\FutureFinanceSearch;
use Yii;
use yii\base\InvalidConfigException;

class FutureStatisticController extends MainController
{
    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $searchModel = Yii::createObject([
            'class' => FutureFinanceSearch::class
        ]);

        $dataProvider = $searchModel->search(app()->request->get() ?? []);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ], true);
    }
}


