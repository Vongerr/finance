<?php

/** @var yii\web\View $this */

use app\entities\Finance;
use app\models\search\FinanceSearch;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\data\ActiveDataProvider;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

$searchModel = Yii::createObject([
    'class' => FinanceSearch::class
]);

$dataProvider = new ActiveDataProvider([
    'query' => Finance::find()
]);

echo GridView::widget([
    'id' => 'pages-id',
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => SerialColumn::class,
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
        ],
        //Информация по приказу
        [
            'class' => DataColumn::class,
            'hAlign' => GridView::ALIGN_CENTER,
            'vAlign' => GridView::ALIGN_TOP,
            'width' => '50px',
            'value' => function ($model) {

                return $model->category;
            },
        ],
    ]
]);
?>
