<?php

namespace app\controllers;

use app\components\View;
use yii\base\ExitException;
use yii\base\Model;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Базовй контроллер для работы в приложении
 *
 * Class Controller
 * @package core\components
 *
 * @property string $title
 * @property string $smallTitle
 * @property-read string $returnUrl
 * @property View $view
 * @property Module $module
 */
class MainController extends Controller
{
    protected const CONTROLLER_TITLE = '';

    /**
     * Установить заголовок 1 уровня
     */
    public function setTitle(string $title): void
    {
        $this->view->setTitle($title);
    }

    /**
     * Установить заголовок 2 уровня
     */
    public function setSmallTitle(string $title): void
    {
        $this->view->setSmallTitle($title);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {

            if (static::CONTROLLER_TITLE) {

                $this->setTitle(static::CONTROLLER_TITLE);
            }

            return true;
        }

        return false;
    }

    /**
     * Проверить ajax валидность данных модели формы
     *
     * @throws ExitException
     */
    public function performAjaxValidation(Model $model): void
    {
        if (
            app()->request->isAjax && $model->load(app()->request->post())
        ) {

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data = ActiveForm::validate($model);
            app()->response->send();

            app()->end();
        }
    }

    public function ajaxRedirect(string $url = null, string $message = null): array|Response
    {
        if (!$url) {

            $url = $this->getReturnUrl();
        }

        if (!app()->request->isAjax) {

            if ($message) {

                app()->session->set('success', $message);
            }

            return $this->redirect($url);

        } else {

            app()->response->format = Response::FORMAT_JSON;

            $data = [
                'success' => true,
                //'url' => $url,
            ];

            if ($message) $data['message'] = $message;

            return $data;
        }
    }

    /**
     * Проверить ajax валидность данных e нескольких моделей формы
     *
     * @param Model[] $models
     * @throws ExitException
     */
    public function performAjaxValidationMultiply(array $models): void
    {
        if (!app()->request->isAjax) return;

        $load = false;
        $post = app()->request->post();

        foreach ($models as $model) {

            $loadModel = $model->load($post);
            $load = $load || $loadModel;
        }

        if ($load) {

            $data = [];
            foreach ($models as $m) {

                $data = ArrayHelper::merge($data, ActiveForm::validate($m));
            }

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data = $data;
            app()->response->send();
            app()->end();
        }
    }

    protected function setReturnUrl(string $url = ''): void
    {
        if (!$url) $url = app()->request->url;

        user()->returnUrl = $url;
    }

    protected function getReturnUrl(): string
    {
        return user()->returnUrl;
    }

    /**
     * @param string $view
     * @param array $params
     */
    public function render($view, $params = [], bool $ajaxScenario = false): string
    {
        if ($params === false) $params = [];

        if ($ajaxScenario) {

            return app()->request->isAjax ? $this->renderAjax($view, $params) : parent::render($view, $params);
        }

        return parent::render($view, $params);
    }
}