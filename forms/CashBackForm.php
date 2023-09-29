<?php

namespace app\forms;

use app\entities\CashBack;
use app\helpers\CategoryHelper;
use app\helpers\MonthHelper;
use yii\base\Model;

class CashBackForm extends Model
{
    public $month = 9;

    public $year = 2023;

    public $category;

    public $individual_category;

    public $percent;

    public function __construct(CashBack $cashBack = null, array $config = [])
    {
        parent::__construct($config);

        if ($cashBack) {

            $this->month = $cashBack->month;
            $this->year = $cashBack->year;
            $this->category = $cashBack->category;
            $this->individual_category = $cashBack->individual_category;
            $this->percent = $cashBack->percent;
        }
    }

    public function rules(): array
    {
        return [
            [['month', 'year', 'category', 'percent'], 'required'],
            [['month'], 'in', 'range' => array_keys(MonthHelper::getList())],
            [['category'], 'in', 'range' => array_keys(CategoryHelper::getList())],
            [['year'], 'integer'],
            [['individual_category'], 'string', 'max' => 25],
            [['percent'], 'integer', 'max' => 100, 'min' => 1],
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

    public function formName(): string
    {
        return 'cash-back-form';
    }
}