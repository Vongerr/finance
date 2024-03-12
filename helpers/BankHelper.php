<?php

namespace app\helpers;

use app\entities\Finance;
use yii\helpers\Html;

class BankHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            Finance::TINKOFF => 'Тинькофф',
            Finance::ALFA => 'Альфа-банк',
            Finance::VTB => 'ВТБ',
            Finance::OTKRITIE => 'Открытие'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::TINKOFF => Html::tag('span', 'Тинькофф', ['class'=>'label label-danger']),
            Finance::ALFA => Html::tag('span', 'Альфа-банк', ['class'=>'label label-success']),
            Finance::VTB => Html::tag('span', 'ВТБ', ['class'=>'label label-success']),
            Finance::OTKRITIE => Html::tag('span', 'Открытие', ['class'=>'label label-success'])
        ];
    }
}