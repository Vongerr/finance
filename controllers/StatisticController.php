<?php

namespace app\controllers;

use app\forms\FinanceForm;
use app\models\search\FinanceSearch;
use app\services\DeleteService;
use app\services\FinanceService;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class StatisticController extends MainController
{
    const FINANCE = 'finance';

    const CATEGORY_FINANCE = 'category-finance';

    const FUTURE_FINANCE = 'future-finance';

    private FinanceService $service;

    private DeleteService $serviceDelete;

    public function __construct(string               $id,
                                                     $module,
                                FinanceService       $service,
                                DeleteService        $serviceDelete,
                                array                $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
        $this->serviceDelete = $serviceDelete;
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {

            //$this->setTitle('Статистика');

            return true;
        }

        return false;
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
        ], true);
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
        ], true);
    }

    public function actionCopy(int $id): Response|array|string
    {
        $model = $this->service->getStatisticById($id);

        $form = new FinanceForm($model);

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {

                $this->service->copy($form);

                return $this->ajaxRedirect(Url::to(['index']));

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ], true);
    }

    public function actionUpdate(int $id): Response|array|string
    {
        $model = $this->service->getStatisticById($id);

        $form = new FinanceForm($model);

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {
                $this->service->update($form, $model);

                return $this->ajaxRedirect();

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ], true);
    }

    public function actionFinance(): string
    {
        return $this->render('finance', [
            'data' => $this->service->defineExpenses(),
        ], true);
    }

    public function actionCategoryFinance(): string
    {
        $financeInfo = $this->service->defineCategoryFinance();

        return $this->render('finance-month', [
            'data' => $financeInfo['data'],
            'categoryList' => $financeInfo['categoryList'],
        ], true);
    }

    public function actionFutureFinance(): string
    {
        return $this->render('future-finance', [
        ], true);
    }

    /**
     * @throws Throwable
     */
    public function actionDelete(int $id): Response|array
    {
        $this->service->remove($id);

        return $this->ajaxRedirect(Url::to(['index']));
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteBank(string $bank): void
    {
        $this->serviceDelete->deleteBank($bank);
    }
}