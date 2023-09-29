<?php

namespace app\helpers;

use app\entities\CashBack;
use yii\helpers\Html;

class MonthHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            CashBack::JANUARY => 'Январь',
            CashBack::FEBRUARY => 'Февраль',
            CashBack::MARCH => 'Март',
            CashBack::APRIL => 'Апрель',
            CashBack::MAY => 'Май',
            CashBack::JUNE => 'Июнь',
            CashBack::JULY => 'Июль',
            CashBack::AUGUST => 'Август',
            CashBack::SEPTEMBER => 'Сентябрь',
            CashBack::OCTOBER => 'Октябрь',
            CashBack::NOVEMBER => 'Ноябрь',
            CashBack::DECEMBER => 'Декабрь'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            CashBack::JANUARY => Html::tag('span', 'Январь', ['class'=>'label label-primary']),
            CashBack::FEBRUARY => Html::tag('span', 'Февраль', ['class'=>'label label-primary']),
            CashBack::MARCH => Html::tag('span', 'Март', ['class'=>'label label-primary']),
            CashBack::APRIL => Html::tag('span', 'Апрель', ['class'=>'label label-primary']),
            CashBack::MAY => Html::tag('span', 'Май', ['class'=>'label label-primary']),
            CashBack::JUNE => Html::tag('span', 'Июнь', ['class'=>'label label-primary']),
            CashBack::JULY => Html::tag('span', 'Июль', ['class'=>'label label-primary']),
            CashBack::AUGUST => Html::tag('span', 'Август', ['class'=>'label label-primary']),
            CashBack::SEPTEMBER => Html::tag('span', 'Сентябрь', ['class'=>'label label-primary']),
            CashBack::OCTOBER => Html::tag('span', 'Октябрь', ['class'=>'label label-primary']),
            CashBack::NOVEMBER => Html::tag('span', 'Ноябрь', ['class'=>'label label-primary']),
            CashBack::DECEMBER => Html::tag('span', 'Декабрь', ['class'=>'label label-primary'])
        ];
    }
}