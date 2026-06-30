<?php

namespace app\widgets;

use Exception;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;
use yii\widgets\Menu;

/**
 * Class MenuFilterWidget
 * @package app\widgets
 */
class MenuFilterWidget extends Widget
{
    const TYPE_PILLS = 'pills';

    const TYPE_TABS = 'tabs';

    const DIRECTION_HORIZONTAL = '';

    const DIRECTION_VERTICAL = 'flex-column';

    public ?Model $searchModel = null;

    public ?bool $existsProperty = true;

    public ?bool $isFormName = true;

    public $action = null;

    public $titleAllButton = null;

    public ?string $type = self::TYPE_PILLS;

    public ?string $direction = self::DIRECTION_HORIZONTAL;

    public ?array $additionalMenu = [];

    public string $attribute;

    public ?bool $assoc = false;

    public ?bool $pjax = true;

    private ?array $_menu = [];

    /**
     * @throws ServerErrorHttpException
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (!($this->searchModel instanceof Model)) {

            throw new ServerErrorHttpException(sprintf('searchModel param "%s" not instance %s class', get_class($this->searchModel), Model::class));
        }

        if ($this->existsProperty && !property_exists($this->searchModel, $this->attribute)) {

            throw new ServerErrorHttpException(sprintf('"%s" property is not a property of the %s class!', $this->attribute, get_class($this->searchModel)));
        }

        if (is_null($this->action)) {

            $this->action = app()->controller->action->id;
        }

        $url = ArrayHelper::merge([$this->action], app()->request->get(null, []));

        $_current = $this->searchModel->{$this->attribute};

        $list = $this->searchModel->getRangeList($this->attribute);

        if (!is_null($this->titleAllButton) && count($list)) {

            $this->_menu[] = [
                'label' => $this->titleAllButton,
                'url' => ArrayHelper::merge(
                    $url,
                    $this->attributeUrlQuery(),
                    $this->additionalMenu
                ),
                'active' => is_null($_current),
            ];
        }

        foreach ($list as $key => $param) {

            $this->_menu[] = [
                'label' => $param,
                'url' => ArrayHelper::merge(
                    $url,
                    $this->attributeUrlQuery($this->assoc ? $key : $param),
                    $this->additionalMenu),
                'active' => !is_null($_current) && $_current == ($this->assoc ? $key : $param),
            ];
        }
    }

    /**
     * @param mixed $value
     * @return array|array[]
     * @throws InvalidConfigException
     */
    private function attributeUrlQuery($value = null): array
    {
        if ($this->isFormName) {

            return [
                $this->searchModel->formName() => [
                    $this->attribute => $value
                ],
            ];
        } else {

            return [$this->attribute => $value];
        }
    }

    /**
     * @throws Exception|Throwable
     */
    public function run(): string
    {
        $items = [];
        foreach ($this->_menu as $item) {
            $active = !empty($item['active']);
            $pjaxAttr = $this->pjax ? '' : ' data-pjax="0"';
            $linkClass = $active ? 'nav-link active' : 'nav-link';
            $item['template'] = '<a class="' . $linkClass . '" href="{url}"' . $pjaxAttr . '>{label}</a>';
            $item['options'] = ['class' => 'nav-item'];
            $items[] = $item;
        }

        return Menu::widget([
            'items' => $items,
            'encodeLabels' => false,
            'options' => [
                'class' => 'nav nav-' . $this->type . ' ' . $this->direction,
            ],
            'linkTemplate' => '<a href="{url}">{label}</a>',
        ]);
    }
}