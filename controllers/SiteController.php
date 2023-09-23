<?php

namespace app\controllers;

use app\models\search\FinanceSearch;
use app\services\FinanceService;
use Exception;
use FinanceForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\gii\Module;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @var FinanceService
     */
    private $service;

    public function __construct($id, $module, $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);

    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionFinance(): string
    {
        $searchModel = Yii::createObject([
            'class' => FinanceSearch::class
        ]);

        $dataProvider = $searchModel->search();

        return $this->render('finance', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string
     * @throws Exception|InvalidConfigException
     */
    public function actionCreate(): string
    {
        $employeeList = [];

        $form = Yii::createObject([
            'class' => FinanceForm::class,
            'employeeList' => $employeeList,
        ]);

        if ($form->load(app()->request->post()) && $form->validate()) {

            try {
                $this->service->create($form);

            } catch (Exception $e) {

                $form->addError('title', $e->getMessage() . (YII_DEBUG ? (PHP_EOL . $e->getTraceAsString()) : ''));
            }
        }

        return $this->render('form', [
            'model' => $form,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
