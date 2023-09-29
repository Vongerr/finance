<?php

namespace app\widgets;

use Exception;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class CustomGridView
 * @package core\widgets
 */
class CustomGridView extends GridView
{

    const TEMPLATE = <<<HTML
    <div class="{prefix}{type} {solid}">
        {panelHeading}
        <div class="box-body">
            {panelBefore}
            {items}
            {panelAfter}
        </div>
        {panelFooter}
    </div>
HTML;
    public $panelPrefix = 'box box-';

    public $panelTemplate = self::TEMPLATE;

    public $panelHeadingTemplate = <<< HTML
    <div class="pull-right">
        {summary}
    </div>
    <h3 class="box-title">{heading}</h3>
    <div class="clearfix"></div>
HTML;

    public $panelFooterTemplate = <<< HTML
    <div class="kv-panel-pager">
        {pager}
    </div>
    {footer}
    <div class="clearfix"></div>
HTML;

    public $itemLabelSingle = 'запись';

    public $itemLabelPlural = 'записей';

    public $resizableColumns = false;

    // only set when $responsive = false
    public $containerOptions = [
        'style' => 'overflow: auto; font-size: 12px;'
    ];

    public $headerRowOptions = [

    ];

    public $filterRowOptions = [

    ];

    public $bordered = true;

    public $export = [
        'fontAwesome' => true,
    ];

    public $striped = false;

    public $condensed = true;

    public $responsive = false;

    public $hover = true;

    public $persistResize = false;

    public function init()
    {
        parent::init();

        if (method_exists(GridView::class, 'initPanel')) {

            if (static::TEMPLATE === $this->panelTemplate) {

                $this->panelTemplate = <<< HTML
{panelHeading}
{panelBefore}
{items}
{panelAfter}
{panelFooter}
HTML;

                $this->panelHeadingTemplate = <<< HTML
    {summary}
    {title}
    <div class="clearfix"></div>
HTML;
            }

            $this->panelHeadingTemplate = strtr($this->panelHeadingTemplate, [
                '<h3 class="box-title">{heading}</h3>'=>'{title}'
            ]);
        }
    }

    /**
     * @throws Exception
     */
    protected function renderPanel()
    {
        if (!$this->bootstrap || !is_array($this->panel) || empty($this->panel)) {
            return;
        }
        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        $heading = ArrayHelper::getValue($this->panel, 'heading', '');
        $footer = ArrayHelper::getValue($this->panel, 'footer', '');
        $before = ArrayHelper::getValue($this->panel, 'before', '');
        $after = ArrayHelper::getValue($this->panel, 'after', '');
        $solid = ArrayHelper::getValue($this->panel, 'solid', false);

        $headingOptions = ArrayHelper::getValue($this->panel, 'headingOptions', []);
        $footerOptions = ArrayHelper::getValue($this->panel, 'footerOptions', []);
        $beforeOptions = ArrayHelper::getValue($this->panel, 'beforeOptions', []);
        $afterOptions = ArrayHelper::getValue($this->panel, 'afterOptions', []);
        $panelHeading = '';
        $panelBefore = '';
        $panelAfter = '';
        $panelFooter = '';

        if ($heading !== false) {
            static::initCss($headingOptions, 'box-header with-border');
            $content = strtr($this->panelHeadingTemplate, ['{heading}' => $heading]);
            $panelHeading = Html::tag('div', $content, $headingOptions);
        }
        if ($footer !== false) {
            static::initCss($footerOptions, 'box-footer');
            $content = strtr($this->panelFooterTemplate, ['{footer}' => $footer]);
            $panelFooter = Html::tag('div', $content, $footerOptions);
        }
        if ($before !== false) {
            static::initCss($beforeOptions, 'kv-panel-before');
            $content = strtr($this->panelBeforeTemplate, ['{before}' => $before]);
            $panelBefore = Html::tag('div', $content, $beforeOptions);
        }
        if ($after !== false) {
            static::initCss($afterOptions, 'kv-panel-after');
            $content = strtr($this->panelAfterTemplate, ['{after}' => $after]);
            $panelAfter = Html::tag('div', $content, $afterOptions);
        }

        $classSolid = $solid ? 'box-solid' : '';

        $this->panelTemplate = strtr($this->panelTemplate, ['{solid}' => $classSolid]);


        $this->layout = strtr(
            $this->panelTemplate,
            [
                '{panelHeading}' => $panelHeading,
                '{prefix}' => $this->panelPrefix,
                '{type}' => $type,
                '{panelFooter}' => $panelFooter,
                '{panelBefore}' => $panelBefore,
                '{panelAfter}' => $panelAfter,
            ]
        );
    }
}