<?php

namespace app\components;

use yii\web\View as BaseView;

/**
 * Class View
 * @package app\components
 *
 * @property bool $isBootstrapIcons
 */
class View extends BaseView
{
    private bool $isBootstrapIcons = false;

    public ?string $bigTitle = null;

    public ?string $smallTitle = null;

    /**
     * Установить заголовок 2 уровня
     */
    public function setSmallTitle(string $title): void
    {
        $this->smallTitle = $title;
    }

    /**
     * Установить заголовок 1го уровня
     */
    public function setTitle($title): void
    {
        $this->bigTitle = $title;
        $this->title = trim(strip_tags($title));
    }

    /**
     * Получить заголовок 2 уровня
     */
    public function getSmallTitle(): ?string
    {
        return $this->smallTitle;
    }


    /**
     * Получить заголовок 1 уровня
     */
    public function getTitle(): ?string
    {
        return $this->bigTitle;
    }

    public function setBreadcrumbs(array $breadcrumbs): void
    {
        $this->params['breadcrumbs'] = $breadcrumbs;
    }

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
    public function setViewParam($key, $value): void
    {
        $this->params['content'][$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getViewParam($key, $defaultValue = null): mixed
    {
        return $this->params['content'][$key]??$defaultValue;
    }

    public function setIsBootstrapIcons(bool $active): void
    {
        $this->isBootstrapIcons = $active;
    }

    public function getIsBootstrapIcons(): bool
    {
        return $this->isBootstrapIcons;
    }
}