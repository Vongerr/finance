<?php

use app\entities\Finance;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\entities\Finance */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Finance';
$this->params['breadcrumbs'][] = $this->title;

$pjaxId = 'finance-pjax';



echo GridView::widget([
    'id' => 'finance-id',
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
                    'title' => 'Добавление записи',
                    'class' => 'btn btn-primary',
                    'data' => [
                        'pjax' => 0,
                        'toggle' => 'modal',
                        'target' => '#grid-modal',
                        'pjax-id' => $pjaxId,
                        'title' => 'Добавление записи',
                        'href' => Url::to(['finance']),
                    ],
                ])
        ],
    ],
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => false,
    'hover' => true,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'footer' => false,
        'after' => false,
    ],
    'panelTemplate' => '<div class="{prefix}{type} {solid}">{panelHeading}<div class="box-body">'
        . '{panelBefore}{items}{panelAfter}</div>{panelFooter}</div>',
    'showFooter' => false,
    'showPageSummary' => false,
    'persistResize' => false,
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
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url) {

                    return Html::a(
                        Html::tag('i', '', ['class' => 'bi bi-pencil']),
                        $url,
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
            'format' => 'raw',
            'value' => function (Finance $model) {

                return CategoryHelper::getValue($model->category, true);
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
        [
            'class' => ActionColumn::class,
            'template' => '{delete}',
            'width' => '30px',
            'hAlign' => GridView::ALIGN_RIGHT,
            'vAlign' => GridView::ALIGN_TOP,
            'buttons' => [
                'delete' => function ($url) {

                    return Html::a('<i class="fa fa-trash"></i>',
                        $url,
                        [
                            'class' => 'btn btn-xs btn-danger ajax-submit',
                            'title' => 'Удаление объявления',
                            'data' => [
                                'pjax' => 0,
                                'confirm-message' => 'Вы действительно желаете удалить данное объявление?',
                                'href' => $url,
                            ],
                        ]);
                },
            ],
        ],
    ]
]);

echo Modal::widget([
    'id' => 'grid-modal',
]);

echo Dialog::widget();
/** @noinspection JSUnusedLocalSymbols */
/** @noinspection ES6ConvertVarToLetConst */
/** @noinspection JSUnresolvedVariable */
/** @noinspection JSCheckFunctionSignatures */
/** @noinspection JSUnresolvedFunction */
/** @noinspection EqualityComparisonWithCoercionJS */
/** @noinspection JSUnfilteredForInLoop */
$this->registerJs(<<<JS
!function ($) {
    $(function() {
        
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        
         $("#grid-modal")
        .on("show.bs.modal", function(e) {
            
            if ($(e.target).attr('id')=='grid-modal') {
            
                if(window.tinyMCE !== undefined && tinyMCE.editors.length){
                    for(var i in tinyMCE.editors){
                        tinyMCE.editors[i].destroy();
                    }
                }
            }
            
            var link = $(e.relatedTarget),
                el = $(this);
                
            if (link.data('target') === '#grid-modal') {
                        
                el.find("h3.modal-title").text(link.data('title'));
            
                el.find(".modal-body").html('').load(link.data("href"), function() {
                
                    el.find(".modal-body form").attr('data-pjax-container', link.data('pjax-id'));
                });
            }
        })
        .on('submit', 'form', function(e) {
        
            e.preventDefault();
            
            var form = $(this),
                pjaxContainer = form.data('pjax-container'),
                formData = new FormData(form[0]);
              
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                cache:false,
                'success': function(res){
                    
                    if (res.success) {
                        
                        $('#grid-modal').modal("toggle");
                        
                        if (pjaxContainer) {
                            $.pjax.reload({container:"#"+pjaxContainer, async:false});
                        } else {
                            location.reload();
                        } 
                        
                    } else {
                        var element = $('#grid-modal');
                        
                        element.find(".modal-body").html(res);
                        element.find(".modal-body form").attr('data-pjax-container', pjaxContainer);
                    } 
                },
                'error': function(){
                    
                   console.log('internal server error');
                }
            });
            
            return false;
        })
        .on('click', 'a.cancel-button', function(e) {
            
            e.preventDefault();
            $('#grid-modal').modal("toggle");
        });
         
         
         
        $('body')
        .on("hidden.bs.modal", ".modal", function (e) { //fire on closing modal box
        
            if ($('.modal:visible').length) { 
                $('body').addClass('modal-open'); 
            }
        })
        .on('click', 'a.ajax-submit', function(e) {
            
             e.preventDefault();
            
            var href = $(this).data('href'),
                confirm = $(this).data('confirm-message'),
                pjaxContainer = $(this).data('pjax-id');
            
            if (confirm) {
                krajeeDialog.confirm(confirm, function(result) {
                    
                    if(result){
                        
                        $.ajax({
                            url: href,
                            type: 'post',
                            'success': function(res){
                                
                                if (res.success) {
                                    
                                    if (pjaxContainer) {
                                    
                                        $.pjax.reload({container:"#"+pjaxContainer});
                                    } else {
                                    
                                        location.reload();
                                    } 
                                } 
                                else{
                                    res.message === undefined
                                        ? console.log('SERVER ERROR')
                                        : console.log(res.message);     
                                    
                                }
                            }
                        });
                    }
                });
            } else {
                $.ajax({
                    url: href,
                    type: 'post',
                    'success': function(res){
                        
                        if (res.success) {
                            
                            if (pjaxContainer) {
                            
                                $.pjax.reload({container:"#"+pjaxContainer});
                            } else {
                            
                                location.reload();
                            } 
                        } 
                    }
                });
            }
        });
        
    });
    
}(window.jQuery)
JS
    , View::POS_END);