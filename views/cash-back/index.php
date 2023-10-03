<?php

use app\assets\PjaxWindowAsset;
use app\components\View;
use app\entities\CashBack;
use app\helpers\CategoryHelper;
use app\helpers\MonthHelper;
use app\models\search\CashBackSearch;
use app\widgets\CustomActionColumn;
use app\widgets\CustomGridView;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridViewInterface;
use kartik\grid\SerialColumn;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this View */
/* @var $searchModel CashBackSearch */
/* @var $dataProvider ActiveDataProvider */

try {
    $this->title = 'Кешбэк';
    $this->params['breadcrumbs'][] = $this->title;

    $pjaxId = 'cash-back-pjax';

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
                    '<i class="glyphicon glyphicon-plus"></i> Добавить категорию кешбэка',
                    Url::to(['create']),
                    [
                        'title' => 'Добавление категории кешбэка',
                        'class' => 'btn btn-success',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Добавление категории кешбэка',
                            'href' => Url::to(['create']),
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
                            Html::tag('i', '', ['class' => 'bi bi-pencil']),
                            $url,
                            [
                                'class' => 'btn btn-warning btn-xs',
                                'title' => 'Изменение категории кешбэка',
                                'data' => [
                                    'pjax' => 0,
                                    'pjax-id' => $pjaxId,
                                    'toggle' => 'modal',
                                    'target' => '#grid-modal',
                                    'title' => 'Изменение категории кешбэка',
                                    'href' => $url,
                                ],
                            ]
                        );
                    },
                ],
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'month',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'format' => 'raw',
                'value' => function (CashBack $model) {

                    return MonthHelper::getValue($model->month);
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'year',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'format' => 'raw',
                'value' => function (CashBack $model) {

                    return $model->year;
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
                'value' => function (CashBack $model) {

                    return CategoryHelper::getValue($model->category, true);
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'individual_category',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'value' => function (CashBack $model) {

                    return $model->individual_category;
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'percent',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'value' => function (CashBack $model) {

                    return $model->percent;
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
                    'delete' => function ($url) use ($pjaxId) {

                        return Html::a(
                            Html::tag('i', '', ['class' => 'bi bi-trash']),
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger ajax-submit',
                                'title' => 'Удаление категории кешбэка',
                                'data' => [
                                    'pjax' => 0,
                                    'pjax-class' => $pjaxId,
                                    'confirm-message' => 'Вы действительно желаете удалить категорию кешбэка?',
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

} catch (Throwable $e) {

    viewException($e);
}