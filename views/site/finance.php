<?php

use app\entities\Finance;
use app\helpers\CategoryBudgetHelper;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\bootstrap4\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\entities\Finance */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Finance';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id' => 'pages-id',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => SerialColumn::class,
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
        ],
        [
            'class' => ActionColumn::class,
            'hAlign' => GridView::ALIGN_LEFT,
            'vAlign' => GridView::ALIGN_TOP,
            'template' => '{create}',
            'buttons' => [
                'create' => function ($url) {

                    return Html::a(
                        Html::tag('i', '', ['class' => 'fa fa-pencil']),
                        'create',
                        [
                            'class' => 'btn btn-warning btn-xs',
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
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
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
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function (Finance $model) {

                return $model->category;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'date',
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function (Finance $model) {

                return date('d.m.Y', strtotime($model->date));
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'time',
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function (Finance $model) {

                return $model->time;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'username',
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function (Finance $model) {

                return $model->username;
            },
        ],
        [
            'class' => DataColumn::class,
            'attribute' => 'money',
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function (Finance $model) {

                return $model->money;
            },
        ],
    ]
]);

echo Modal::widget([
    'id' => 'grid-modal',
]);

echo Dialog::widget();