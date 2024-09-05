<?php

namespace app\helpers;

use app\entities\Finance;
use yii\helpers\Html;

class CategoryHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            Finance::TAXI => 'Такси',
            Finance::CAFE => 'Питание',
            Finance::RESTAURANT => 'Рестораны',
            Finance::FAST_FOOD => 'Фастфуд',
            Finance::MARKET => 'Супермаркеты',
            Finance::TRANSPORT => 'Общественный транспорт',
            Finance::SCHOLARSHIP => 'Стипендия',
            Finance::SALARY => 'Зарплата',
            Finance::TRANSFER => 'Переводы',
            Finance::CASH . Finance::TRANSFER => 'Наличные + переводы',
            Finance::SPORT => 'Спорттовары',
            Finance::ELECTRONIC => 'Электроника и техника',
            Finance::MEDICAL => 'Медицина',
            Finance::BEAUTY => 'Красота',
            Finance::PHARMACY => 'Аптека',
            Finance::ZOO => 'Зоомагазин',
            Finance::DIGITAL_STORE => 'Онлайн покупки',
            Finance::REPAIR => 'Дом и ремонт',
            Finance::STATIONARY => 'Канцтовары',
            Finance::EDUCATION => 'Образование',
            Finance::COMMUNICATION => 'Мобильная связь',
            Finance::ENTERTAINMENTS => 'Развлечения',
            Finance::CASH => 'Наличные',
            Finance::FINANCE => 'Финансы',
            Finance::OTHER => 'Другое'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::TAXI => Html::tag('span', 'Такси', ['class' => 'label label-warning']),
            Finance::CAFE => Html::tag('span', 'Питание', ['class' => 'label label-success']),
            Finance::RESTAURANT => Html::tag('span', 'Рестораны', ['class' => 'label label-success']),
            Finance::FAST_FOOD => Html::tag('span', 'Фастфуд', ['class' => 'label label-success']),
            Finance::MARKET => Html::tag('span', 'Супермаркет', ['class' => 'label label-success']),
            Finance::TRANSPORT => Html::tag('span', 'Общественный транспорт', ['class' => 'label label-default']),
            Finance::SCHOLARSHIP => Html::tag('span', 'Стипендия', ['class' => 'label label-primary']),
            Finance::SALARY => Html::tag('span', 'Зарплата', ['class' => 'label label-primary']),
            Finance::TRANSFER => Html::tag('span', 'Переводы', ['class' => 'label label-primary']),
            Finance::SPORT => Html::tag('span', 'Спорттовары', ['class' => 'label label-default']),
            Finance::ELECTRONIC => Html::tag('span', 'Электроника', ['class' => 'label label-default']),
            Finance::MEDICAL => Html::tag('span', 'Медицина', ['class' => 'label label-default']),
            Finance::BEAUTY => Html::tag('span', 'Красота', ['class' => 'label label-default']),
            Finance::PHARMACY => Html::tag('span', 'Аптека', ['class' => 'label label-default']),
            Finance::ZOO => Html::tag('span', 'Зоомагазин', ['class' => 'label label-default']),
            Finance::DIGITAL_STORE => Html::tag('span', 'Онлайн покупки', ['class' => 'label label-default']),
            Finance::REPAIR => Html::tag('span', 'Дом и ремонт', ['class' => 'label label-default']),
            Finance::STATIONARY => Html::tag('span', 'Канцеллярские товары', ['class' => 'label label-default']),
            Finance::EDUCATION => Html::tag('span', 'Образование', ['class' => 'label label-default']),
            Finance::COMMUNICATION => Html::tag('span', 'Мобильная связь', ['class' => 'label label-default']),
            Finance::ENTERTAINMENTS => Html::tag('span', 'Развлечения', ['class' => 'label label-default']),
            Finance::CASH => Html::tag('span', 'Наличные', ['class' => 'label label-danger']),
            Finance::FINANCE => Html::tag('span', 'Финансы', ['class' => 'label label-danger']),
            Finance::OTHER => Html::tag('span', 'Другое', ['class' => 'label label-info']),
        ];
    }
}