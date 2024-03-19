<?php

namespace app\helpers;

use app\entities\Finance;
use yii\helpers\Html;

class ExclusionHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            Finance::NO_EXCLUSION => 'Не исключено',
            Finance::EXCLUSION => 'Исключено',
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::NO_EXCLUSION => Html::tag('span', 'Не исключено', ['class' => 'label label-success']),
            Finance::EXCLUSION => Html::tag('span', 'Исключено', ['class' => 'label label-danger']),
        ];
    }
}