<?php

namespace app\controllers;

use app\forms\CashBackForm;
use app\models\search\CashBackSearch;
use app\services\CashBackService;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\Response;

class CashBackController extends Controller
{
    /**
     * @var CashBackService
     */
    private $service;

    public function __construct(string $id, $module, CashBackService $service, array $config = [])
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
            'class' => CashBackSearch::class
        ]);

        $dataProvider = $searchModel->search(app()->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $form = new CashBackForm();

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {

                $this->service->create($form);

                return $this->ajaxRedirect();

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->service->getStatisticById($id);

        $form = new CashBackForm($model);

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
        ]);
    }

    /**
     * @throws Throwable
     */
    public function actionDelete(int $id)
    {
        $this->service->remove($id);

        return $this->ajaxRedirect();
    }

    protected function ajaxRedirect(string $url = null, string $message = null)
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