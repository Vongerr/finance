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
            Finance::OTHER => 'Другое'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::TAXI => Html::tag('span', 'Такси', ['class' => 'label label-warning']),
            Finance::CAFE => Html::tag('span', 'Питание', ['class' => 'label label-warning']),
            Finance::RESTAURANT => Html::tag('span', 'Рестораны', ['class' => 'label label-warning']),
            Finance::FAST_FOOD => Html::tag('span', 'Фастфуд', ['class' => 'label label-primary']),
            Finance::MARKET => Html::tag('span', 'Супермаркет', ['class' => 'label label-primary']),
            Finance::TRANSPORT => Html::tag('span', 'Общественный транспорт', ['class' => 'label label-primary']),
            Finance::SCHOLARSHIP => Html::tag('span', 'Стипендия', ['class' => 'label label-primary']),
            Finance::SALARY => Html::tag('span', 'Зарплата', ['class' => 'label label-primary']),
            Finance::TRANSFER => Html::tag('span', 'Переводы', ['class' => 'label label-primary']),
            Finance::SPORT => Html::tag('span', 'Спорттовары', ['class' => 'label label-primary']),
            Finance::ELECTRONIC => Html::tag('span', 'Электроника', ['class' => 'label label-primary']),
            Finance::MEDICAL => Html::tag('span', 'Медицина', ['class' => 'label label-primary']),
            Finance::BEAUTY => Html::tag('span', 'Красота', ['class' => 'label label-primary']),
            Finance::PHARMACY => Html::tag('span', 'Аптека', ['class' => 'label label-primary']),
            Finance::ZOO => Html::tag('span', 'Зоомагазин', ['class' => 'label label-primary']),
            Finance::DIGITAL_STORE => Html::tag('span', 'Онлайн покупки', ['class' => 'label label-primary']),
            Finance::REPAIR => Html::tag('span', 'Дом и ремонт', ['class' => 'label label-primary']),
            Finance::STATIONARY => Html::tag('span', 'Канцеллярские товары', ['class' => 'label label-primary']),
            Finance::EDUCATION => Html::tag('span', 'Образование', ['class' => 'label label-primary']),
            Finance::COMMUNICATION => Html::tag('span', 'Мобильная связь', ['class' => 'label label-primary']),
            Finance::ENTERTAINMENTS => Html::tag('span', 'Развлечения', ['class'=>'label label-primary']),
            Finance::CASH => Html::tag('span', 'Наличные', ['class' => 'label label-primary']),
            Finance::OTHER => Html::tag('span', 'Другое', ['class' => 'label label-primary']),
        ];
    }
}