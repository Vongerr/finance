<?php

use app\assets\AppAsset;
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
use yii\bootstrap5\Modal;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this View */
/* @var $searchModel CashBackSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Кешбэк';
$this->params['breadcrumbs'][] = $this->title;

$pjaxId = 'cash-back-pjax';

AppAsset::register($this);

try {
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
                            '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
                            $url,
                            [
                                'class' => 'btn btn-warning btn-xs',
                                'pjax-class' => $pjaxId,
                                'toggle' => 'modal',
                                'target' => '#grid-modal',
                                'title' => 'Изменение категории кешбэка',
                                'data' => [
                                    'pjax' => 0,
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

                    return MonthHelper::getValue($model->month, true);
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
                    'delete' => function ($url) {

                        return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger ajax-submit',
                                'title' => 'Удаление категории кешбэка',
                                'data' => [
                                    'pjax' => 0,
                                    'confirm-message' => 'Вы действительно желаете удалить категорию кешбэка?',
                                    'href' => $url,
                                ],
                            ]);
                    },
                ],
            ]
        ]
    ]);
} catch (Throwable $e) {
}

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

$this->registerCss(
    <<<CSS

.glyphicon {
    font-family: "Font Awesome 5 Free",serif;
    font-weight: 900;
}

.glyphicon-pencil::before {
    content: "\\\f044";
}

.glyphicon-trash::before {
    content: "\\\f2ed";
}

.glyphicon-eye-open::before {
    content: "\\\f06e";
}
CSS

);