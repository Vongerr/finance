<?php

use app\forms\FinanceForm;
use app\helpers\BankHelper;
use app\helpers\CategoryAllHelper;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

/** @var View $this */
/** @var FinanceForm $model */

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
        <div class="col-sm-6">
            <?php echo $form->field($model, 'category')->dropDownList(CategoryAllHelper::getList()); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'budget_category')->dropDownList(CategoryBudgetHelper::getList()); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->field($model, 'date')->widget(
                DatePicker::class,
                [
                    'language' => 'ru',
                    'options' => [
                        'value' => $model->date ? date('d.m.Y', strtotime($model->date)) : '',
                        'placeholder' => 'Выберите дату ...',
                    ]
                ]
            ); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'time'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->field($model, 'money'); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'username'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->field($model, 'bank')->dropDownList(BankHelper::getList()); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'comment'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form->field($model, 'exclusion')->checkbox([0 => 'Не исключение', 1 => 'Исключение']); ?>
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