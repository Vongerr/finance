<?php

namespace app\entities;

use app\helpers\CategoryHelper;
use app\helpers\MonthHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "attendance__absenteeism_periods".
 *
 * @property int $id
 * @property int $month Месяц
 * @property int $year Год
 * @property string $category Категория
 * @property string $individual_category Индивидуальная категория
 * @property int $percent Процент
 * @property string $created_at
 * @property string $updated_at
 */
class CashBack extends ActiveRecord
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    public static function create($form): CashBack
    {
        $finance = new static();

        $finance->edit($form);

        return $finance;
    }

    public function edit($form)
    {
        $this->month = $form->month;
        $this->year = $form->year;
        $this->category = $form->category;
        $this->individual_category = $form->individual_category;
        $this->percent = $form->percent;
    }

    public static function tableName(): string
    {
        return 'cash_back';
    }

    public function rules(): array
    {
        return [
            [['month', 'year', 'category', 'percent'], 'required'],
            [['month'], 'in', 'range' => array_keys(MonthHelper::getList())],
            [['category'], 'in', 'range' => array_keys(CategoryHelper::getList())],
            [['year'], 'integer'],
            [['individual_category'], 'string', 'max' => 25],
            [['percent'], 'integer', 'min' => 1, 'max' => 100]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'month' => 'Месяц',
            'year' => 'Год',
            'category' => 'Категория',
            'individual_category' => 'Индивидуальная категория',
            'percent' => 'Процент',
        ];
    }
}
