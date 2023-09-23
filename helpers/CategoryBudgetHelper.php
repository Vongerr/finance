<?php

namespace app\helpers;

use app\entities\Finance;
use yii\helpers\Html;

class CategoryBudgetHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            Finance::EXPENSES => 'Расходы',
            Finance::REVENUE => 'Доходы'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::EXPENSES => Html::tag('span', 'Расходы', ['class'=>'label label-success']),
            Finance::REVENUE => Html::tag('span', 'Доходы', ['class'=>'label label-warning'])
        ];
    }
}