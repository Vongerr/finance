<?php

namespace app\helpers;

use app\entities\Finance;
use yii\helpers\Html;

class CategoryAllHelper extends ListHelper
{
    public static function getList(): array
    {
        return [
            Finance::TAXI => 'Такси',
            Finance::CAFE => 'Питание',
            Finance::RESTAURANT => 'Рестораны',
            Finance::FAST_FOOD => 'Фастфуд',
            Finance::MARKET => 'Супермаркеты',
            Finance::PRODUCTS => 'Продукты',
            Finance::TRANSPORT => 'Общественный транспорт',
            Finance::SCHOLARSHIP => 'Стипендия',
            Finance::SALARY => 'Зарплата',
            Finance::TRANSFER => 'Переводы',
            Finance::SPORT => 'Спорттовары',
            Finance::ELECTRONIC => 'Электроника и техника',
            Finance::MEDICAL => 'Медицина',
            Finance::BEAUTY => 'Красота',
            Finance::PHARMACY => 'Аптеки',
            Finance::ZOO => 'Зоомагазин',
            Finance::DIGITAL_STORE => 'Онлайн покупки',
            Finance::REPAIR => 'Дом и ремонт',
            Finance::STATIONARY => 'Канцтовары',
            Finance::EDUCATION => 'Образование',
            Finance::COMMUNICATION => 'Мобильная связь',
            Finance::BONUS => 'Бонусы',
            Finance::MARKETPLACE => 'Маркетплейсы',
            Finance::FUEL => 'Топливо',
            Finance::LOTTERIES => 'Азартные игры и лотереи',
            Finance::BOOKS => 'Книги',
            Finance::SOUVENIRS => 'Сувениры',
            Finance::ENTERTAINMENTS => 'Развлечения',
            Finance::KIDS => 'Детские товары',
            Finance::COSMETICS => 'Косметика',
            Finance::CLOTHES_AND_SHOES => 'Одежда и обувь',
            Finance::GOV_SERVICE => 'Госуслуги',
            Finance::TRAIN_TICKET => 'Ж/д билеты',
            Finance::BANK_SERVICE => 'Услуги банка',
            Finance::CASH => 'Наличные',
            Finance::HOTELS => 'Отели',
            Finance::SERVICE => 'Сервис',
            Finance::FINANCE => 'Финансы',
            Finance::CHARITY => 'Благотворительность',
            Finance::AUTO => 'Автоуслуги',
            Finance::REFUND => 'Возврат',
            Finance::OTHER => 'Другое'
        ];
    }

    public static function getHtmlList(): array
    {
        return [
            Finance::BONUS => Html::tag('span', 'Бонус', ['class' => 'label label-default']),
            Finance::MARKETPLACE => Html::tag('span', 'Маркетплейсы', ['class' => 'label label-default']),
            Finance::FUEL => Html::tag('span', 'Топливо', ['class' => 'label label-default']),
            Finance::LOTTERIES => Html::tag('span', 'Азартные игры и лотереи', ['class' => 'label label-default']),
            Finance::BOOKS => Html::tag('span', 'Книги', ['class' => 'label label-default']),
            Finance::SOUVENIRS => Html::tag('span', 'Сувениры', ['class' => 'label label-default']),
            Finance::KIDS => Html::tag('span', 'Детские товары', ['class' => 'label label-default']),
            Finance::COSMETICS => Html::tag('span', 'Косметика', ['class' => 'label label-default']),
            Finance::CLOTHES_AND_SHOES => Html::tag('span', 'Одежда и обувь', ['class' => 'label label-default']),
            Finance::GOV_SERVICE => Html::tag('span', 'Госуслуги', ['class' => 'label label-default']),
            Finance::TRAIN_TICKET => Html::tag('span', 'Ж/д билеты', ['class' => 'label label-default']),
            Finance::BANK_SERVICE => Html::tag('span', 'Услуги банка', ['class' => 'label label-default']),
            Finance::HOTELS => Html::tag('span', 'Отели', ['class' => 'label label-default']),
            Finance::SERVICE => Html::tag('span', 'Сервис', ['class' => 'label label-default']),
            Finance::CHARITY => Html::tag('span', 'Благотворительность', ['class' => 'label label-default']),
            Finance::AUTO => Html::tag('span', 'Автоуслуги', ['class' => 'label label-default']),
            Finance::REFUND => Html::tag('span', 'Возврат', ['class' => 'label label-danger']),
            Finance::TAXI => Html::tag('span', 'Такси', ['class' => 'label label-warning']),
            Finance::CAFE => Html::tag('span', 'Питание', ['class' => 'label label-success']),
            Finance::RESTAURANT => Html::tag('span', 'Рестораны', ['class' => 'label label-success']),
            Finance::FAST_FOOD => Html::tag('span', 'Фастфуд', ['class' => 'label label-success']),
            Finance::MARKET => Html::tag('span', 'Супермаркет', ['class' => 'label label-success']),
            Finance::PRODUCTS => Html::tag('span', 'Продукты', ['class' => 'label label-success']),
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