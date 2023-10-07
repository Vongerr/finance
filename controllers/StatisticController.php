<?php

namespace app\controllers;

use app\forms\FinanceForm;
use app\models\search\FinanceSearch;
use app\services\FinanceService;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class StatisticController extends Controller
{
    /**
     * @var FinanceService
     */
    private FinanceService $service;

    public function __construct(string $id, $module, FinanceService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $searchModel = Yii::createObject([
            'class' => FinanceSearch::class
        ]);

        $dataProvider = $searchModel->search(app()->request->get() ?? []);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate(): Response|array|string
    {
        $form = new FinanceForm();

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {

                $this->service->create($form);

                return $this->ajaxRedirect(Url::to(['index']));

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ]);
    }

    public function actionUpdate(int $id): Response|array|string
    {
        $model = $this->service->getStatisticById($id);

        $form = new FinanceForm($model);

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {
                $this->service->update($form, $model);

                return $this->ajaxRedirect(Url::to(['index']));

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ]);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionFinance(): string
    {
        $searchModel = Yii::createObject([
            'class' => FinanceSearch::class
        ]);

        $data = $searchModel->getFinanceInfo();

        return $this->render('finance', [
            'data' => $data,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function actionDelete(int $id): Response|array
    {
        $this->service->remove($id);

        return $this->ajaxRedirect(Url::to(['index']));
    }

    protected function ajaxRedirect(string $url = null, string $message = null): Response|array
    {
        if (!$url) {

            $url = user()->returnUrl;
        }

        if (!app()->request->isAjax) {

            if ($message) {

                user()->setSuccessFlash($message);
            }

            return $this->redirect($url);

        } else {

            app()->response->format = Response::FORMAT_JSON;

            $data = [
                'success' => true,
                'url' => $url,
            ];

            if ($message) {

                $data['message'] = $message;
            }

            return $data;
        }
    }
}