<?php

namespace app\components;

use yii\web\View as BaseView;

/**
 * Class View
 * @package core\components
 *
 * @property bool $isBootstrapIcons
 */
class View extends BaseView
{
    private $isBootstrapIcons = false;

    /**
     * @var string/null - Заголовок 2 уровня страницы
     */
    public $bigTitle = null;

    /**
     * @var string/null - Заголовок 2 уровня страницы
     */
    public $smallTitle = null;

    /**
     * Установить заголовок 2 уровня
     *
     * @param string $title
     */
    public function setSmallTitle($title)
    {
        $this->smallTitle = $title;
    }

    /**
     * Установить заголовок 1го уровня
     * @param $title
     */
    public function setTitle($title)
    {
        $this->bigTitle = $title;
        $this->title = trim(strip_tags($title));
    }

    /**
     * Получить заголовок 2 уровня
     *
     * @return string
     */
    public function getSmallTitle()
    {
        return $this->smallTitle;
    }


    /**
     * Получить заголовок 1 уровня
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->bigTitle;
    }


    /**
     * Установить "Хлебный крошки"
     *
     * @param array $breadcrumbs
     */
    public function setBreadcrumbs(array $breadcrumbs)
    {
        $this->params['breadcrumbs'] = $breadcrumbs;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return empty($this->params['breadcrumbs'])
            ? [
                [
                    'label' => $this->getTitle(),
                    'url' => ['index'],
                    'encode' => false,
                ],
                [
                    'label' => $this->getSmallTitle(),
                    'encode' => false,
                ]
            ]
            : $this->params['breadcrumbs'];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setViewParam($key, $value)
    {
        $this->params['content'][$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getViewParam($key, $defaultValue = null)
    {
        return $this->params['content'][$key]??$defaultValue;
    }

    public function setIsBootstrapIcons(bool $active)
    {
        $this->isBootstrapIcons = $active;
    }

    public function getIsBootstrapIcons(): bool
    {
        return $this->isBootstrapIcons;
    }
}