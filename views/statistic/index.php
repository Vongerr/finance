<?php

use app\assets\PjaxWindowAsset;
use app\components\View;
use app\entities\Finance;
use app\helpers\BankHelper;
use app\helpers\CategoryAllHelper;
use app\helpers\CategoryBudgetHelper;
use app\helpers\ExclusionHelper;
use app\models\search\FinanceSearch;
use app\widgets\CustomActionColumn;
use app\widgets\CustomGridView;
use app\widgets\MenuFilterWidget;
use app\widgets\Modal;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridViewInterface;
use kartik\grid\SerialColumn;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this View */
/* @var $searchModel FinanceSearch */
/* @var $dataProvider ActiveDataProvider */

try {
    $this->title = 'Финансы';
    $this->params['breadcrumbs'][] = $this->title;

    $pjaxId = 'finance-pjax';

    PjaxWindowAsset::register($this);

    echo CustomGridView::widget([
        'id' => 'finance-statistic',
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
                    'Финансы',
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
                            'href' => Url::to(['finance', 'category' => $searchModel->category]),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Будущие финансы',
                    Url::to(['finance']),
                    [
                        'title' => 'Будущие финансы',
                        'class' => 'btn btn-primary',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Будущие финансы',
                            'href' => Url::to(['future-finance']),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Импортировать Тинькофф',
                    Url::to(['import-finance-tinkoff']),
                    [
                        'title' => 'Импортировать финансы',
                        'class' => 'btn btn-warning',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Импортировать финансы',
                            'href' => Url::to(['import-finance-tinkoff']),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Импортировать Альфа-банк',
                    Url::to(['import-finance-alpha']),
                    [
                        'title' => 'Импортировать финансы',
                        'class' => 'btn btn-warning',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Импортировать финансы',
                            'href' => Url::to(['import-finance-alpha']),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Импортировать Открытие',
                    Url::to(['import-finance-otkritie']),
                    [
                        'title' => 'Импортировать финансы',
                        'class' => 'btn btn-warning',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Импортировать финансы',
                            'href' => Url::to(['import-finance-otkritie']),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Экспорт финансов',
                    Url::to(['export-finance']),
                    [
                        'title' => 'Экспортировать финансы',
                        'class' => 'btn btn-success',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Экспортировать финансы',
                            'href' => Url::to(['export-finance']),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Удалить информацию Тинькофф',
                    Url::to(['delete-bank']),
                    [
                        'title' => 'Удалить информацию',
                        'class' => 'btn btn-danger',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Удалить информацию',
                            'href' => Url::to(['delete-bank', 'bank' => Finance::TINKOFF]),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Удалить информацию Альфа-банк',
                    Url::to(['delete-bank']),
                    [
                        'title' => 'Удалить информацию',
                        'class' => 'btn btn-danger',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Удалить информацию',
                            'href' => Url::to(['delete-bank', 'bank' => Finance::ALFA]),
                        ],
                    ])
            ],
            [
                'content' => Html::a(
                    'Удалить информацию Открытие',
                    Url::to(['delete-bank']),
                    [
                        'title' => 'Удалить информацию',
                        'class' => 'btn btn-danger',
                        'data' => [
                            'pjax' => 0,
                            'toggle' => 'modal',
                            'target' => '#grid-modal',
                            'pjax-id' => $pjaxId,
                            'title' => 'Удалить информацию',
                            'href' => Url::to(['delete-bank', 'bank' => Finance::OTKRITIE]),
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
                'attribute' => 'year',
                'assoc' => true,
                'titleAllButton' => 'Все',
            ]) . Html::tag('br')
            . MenuFilterWidget::widget([
                'searchModel' => $searchModel,
                'attribute' => 'month',
                'assoc' => true,
                'titleAllButton' => 'Все',
            ]) . Html::tag('br')
            . MenuFilterWidget::widget([
                'searchModel' => $searchModel,
                'attribute' => 'bank',
                'assoc' => true,
                'titleAllButton' => 'Все',
            ]) . Html::tag('br')
            . MenuFilterWidget::widget([
                'searchModel' => $searchModel,
                'attribute' => 'exclusion',
                'assoc' => true,
                'titleAllButton' => 'Все',
            ]) . Html::tag('br')
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
                            Html::tag('i', '', ['class' => 'bi bi-pencil', 'style' => 'height:30px; width:30px']),
                            $url,
                            [
                                'class' => 'btn btn-warning btn-xs',
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
                'label' => 'Бюджет',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'format' => 'raw',
                'width' => '50px',
                'options' => [
                    'style' => 'width: 50px;'
                ],
                'value' => function (Finance $model) {

                    return CategoryBudgetHelper::getValue($model->budget_category, true);
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'category',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Finance $model) {

                    return CategoryAllHelper::getValue($model->category, true);
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'exclusion',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Finance $model) {

                    return ExclusionHelper::getValue($model->exclusion, true);
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
                'attribute' => 'date_time',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'value' => function (Finance $model) {

                    return $model->date_time ? date('d.m.Y H:i', strtotime($model->date_time)) : '';
                },
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'bank',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'filter' => false,
                'value' => function (Finance $model) {

                    return BankHelper::getValue($model->bank);
                },
            ],
            /*[
                'class' => DataColumn::class,
                'attribute' => 'username',
                'hAlign' => GridViewInterface::ALIGN_CENTER,
                'vAlign' => GridViewInterface::ALIGN_TOP,
                'width' => '30px',
                'value' => function (Finance $model) {

                    return $model->username;
                },
            ],*/
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

                        return Html::a('<i class="bi bi-trash" aria-hidden="true"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger ajax-submit',
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

} catch (Throwable $e) {

    viewException($e);
}
