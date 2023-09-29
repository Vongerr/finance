<?php

use app\forms\CashBackForm;
use app\helpers\CategoryHelper;
use app\helpers\MonthHelper;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

/** @var View $this */
/** @var CashBackForm $model */

try {
    $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
    echo $form->errorSummary($model);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <?php echo $form->field($model, 'month')->dropDownList(MonthHelper::getList()); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'year'); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'category')->dropDownList(CategoryHelper::getList()); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'individual_category'); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'percent'); ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('Сохранить',
                [
                    'class' => 'btn btn-primary btn-sm',
                    'name' => 'submit-type',
                    'value' => user()->returnUrl,
                ]
            ); ?>&nbsp;
            <?= Html::a('Отмена', user()->returnUrl,
                [
                    'class' => 'btn btn-default btn-sm cancel-button',
                ]
            ); ?>
        </div>
    </div>
    <?php
    $form::end();

} catch (Exception $e) {

    viewException($e);
}