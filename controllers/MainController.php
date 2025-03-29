<?php

namespace app\controllers;

use app\components\View;
use yii\base\ExitException;
use yii\base\Model;
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
 * $property Module $module
 */
class MainController extends Controller
{
    protected const CONTROLLER_TITLE = '';

    /**
     * Установить заголовок 1 уровня
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->view->setTitle($title);
    }

    /**
     * Установить заголовок 2 уровня
     *
     * @param string $title
     */
    public function setSmallTitle(string $title): void
    {
        $this->view->setSmallTitle($title);
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
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
     * @param Model $model
     * @throws ExitException
     */
    public function performAjaxValidation(Model $model)
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


    /**
     * @param string|null $url
     * @param string|null $message
     * @return bool[]|Response
     */
    public function ajaxRedirect(string $url = null, string $message = null): array|Response
    {
        if (!$url) {

            $url = $this->getReturnUrl();
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
                //'url' => $url,
            ];

            if ($message) {

                $data['message'] = $message;
            }

            return $data;
        }
    }


    /**
     * Проверить ajax валидность данных e нескольких моделей формы
     *
     * @param Model[] $models
     * @throws ExitException
     */
    public function performAjaxValidationMultiply(array $models)
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


    /**
     * @param string $url
     */
    protected function setReturnUrl($url = '')
    {
        if (!$url) {

            $url = app()->request->url;
        }

        user()->returnUrl = $url;
    }

    /**
     * @return string
     */
    protected function getReturnUrl()
    {
        return user()->returnUrl;
    }


    /**
     * @param string $view
     * @param array $params
     * @param bool $ajaxScenario
     * @return string
     */
    public function render($view, $params = [], $ajaxScenario = false)
    {
        if ($params === false) {

            $params = [];
        }

        if ($ajaxScenario) {

            if (app()->request->isAjax) {

                return $this->renderAjax($view, $params);
            } else {

                return parent::render($view, $params);
            }
        }

        return parent::render($view, $params);
    }
}