<?php

use app\assets\AppAsset;
use app\assets\PjaxWindowAsset;
use app\components\View;
use app\entities\Finance;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use app\models\search\FinanceSearch;
use app\widgets\CustomActionColumn;
use app\widgets\CustomGridView;
use app\widgets\MenuFilterWidget;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridViewInterface;
use kartik\grid\SerialColumn;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this View */
/* @var $searchModel FinanceSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Финансы';
$this->params['breadcrumbs'][] = $this->title;

$pjaxId = 'finance-pjax';

PjaxWindowAsset::register($this);

echo CustomGridView::widget([
    'id' => $pjaxId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            'id' => $pjaxId,
        ],
    ],
    'containerOptions' => ['style' => 'overflow: auto'],
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'toolbar' => [
        [
            'content' => Html::a(
                '<i class="glyphicon glyphicon-plus"></i> Добавить запись',
                Url::to(['create']),
                [
                    'title' => 'Добавление записи',
                    'class' => 'btn btn-success',
                    'data' => [
                        'pjax' => 0,
                        'toggle' => 'modal',
                        'target' => '#grid-modal',
                        'pjax-id' => $pjaxId,
                        'title' => 'Добавление записи',
                        'href' => Url::to(['create']),
                    ],
                ])
        ],
        [
            'content' => Html::a(
                '<i class="glyphicon glyphicon-plus"></i> Финансы',
                Url::to(['finance']),
                [
                    'title' => 'Финансы',
                    'class' => 'btn btn-primary',
                    'data' => [
                        'pjax' => 0,
                        'toggle' => 'modal',
                        'target' => '#grid-modal',
                        'pjax-id' => $pjaxId,
                        'title' => 'Финансы',
                        'href' => Url::to(['finance']),
                    ],
                ])
        ],
    ],
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => false,
    'export' => [
        'fontAwesome' => true
    ],
    'hover' => true,
    'panel' => [
        'type' => GridViewInterface::TYPE_DEFAULT,
    ],
    'panelTemplate' => '<div class="{prefix}{type} {solid}">{panelHeading}<div class="box-body">'
        . MenuFilterWidget::widget([
            'searchModel' => $searchModel,
            'attribute' => 'category',
            'assoc' => true,
            'titleAllButton' => 'Все',
        ]) . Html::tag('br')
        . MenuFilterWidget::widget([
            'searchModel' => $searchModel,
            'attribute' => 'budget_category',
            'assoc' => true,
            'titleAllButton' => 'Все',
        ]) . Html::tag('br')
        . '{panelBefore}{items}{panelAfter}</div>{panelFooter}</div>',
    'showFooter' => false,
    'showPageSummary' => false,
    'persistResize' => false,
    'columns' => [
        [
            'class' => SerialColumn::class,
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
        ],
        [
            'class' => CustomActionColumn::class,
            'hAlign' => GridViewInterface::ALIGN_LEFT,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'template' => '{update}',
            'width' => '30px',
            'buttons' => [
                'update' => function ($url) use ($pjaxId) {

                    return Html::a(
                        Html::tag('i', '', ['class' => 'fa fa-pencil', 'style' => 'height:30px; width:30px']),
                        $url,
                        [
                            'class' => 'btn btn-warning btn-xs',
                            'style' => 'height:25px; width:25px',
                            'pjax-class' => $pjaxId,
                            'title' => 'Изменение',
                            'data' => [
                                'pjax' => 0,
                                'toggle' => 'modal',
                                'target' => '#grid-modal',
                                'title' => 'Изменение',
                                'href' => $url,
                            ],
                        ]
                    );
                },
            ],
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'budget_category',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'filter' => false,
            'format' => 'raw',
            'value' => function (Finance $model) {

                return CategoryBudgetHelper::getValue($model->budget_category, true);
            },
        ],
        //Информация по приказу
        [
            'class' => DataColumn::class,
            'attribute' => 'category',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'filter' => false,
            'format' => 'raw',
            'value' => function (Finance $model) {

                return CategoryHelper::getValue($model->category, true);
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'date',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'value' => function (Finance $model) {

                return date('d.m.Y', strtotime($model->date));
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'money',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'value' => function (Finance $model) {

                return $model->money;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'time',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'value' => function (Finance $model) {

                return $model->time;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'username',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'value' => function (Finance $model) {

                return $model->username;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'comment',
            'hAlign' => GridViewInterface::ALIGN_CENTER,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'value' => function (Finance $model) {

                return $model->comment ?? '';
            },
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{delete}',
            'header' => '',
            'width' => '30px',
            'hAlign' => GridViewInterface::ALIGN_RIGHT,
            'vAlign' => GridViewInterface::ALIGN_TOP,
            'buttons' => [
                'delete' => function ($url) {

                    return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>',
                        $url,
                        [
                            'class' => 'btn btn-xs btn-danger ajax-submit',
                            'style' => 'height:25px; width:25px',
                            'title' => 'Удаление',
                            'data' => [
                                'pjax' => 0,
                                'confirm-message' => 'Вы действительно желаете удалить данный пункт?',
                                'href' => $url,
                            ],
                        ]);
                },
            ],
        ]
    ]
]);

echo Modal::widget([
    'id' => 'grid-modal',
]);

echo Dialog::widget();