<?php

namespace app\entities;

use app\forms\FinanceForm;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "attendance__absenteeism_periods".
 *
 * @property int $id
 * @property string $budget_category Категория бюджета
 * @property string $category Категория
 * @property string $date Дата операции
 * @property string $time Время операции
 * @property string $username Имя пользователя
 * @property float $money Средства
 * @property float $comment Комментарий
 * @property string $created_at
 * @property string $updated_at
 */
class Finance extends ActiveRecord
{
    const REVENUE = 'revenue'; // доходы
    const EXPENSES = 'expenses'; // расходы

    const TAXI = 'taxi';
    const CAFE = 'cafe';
    const FAST_FOOD = 'fast_food';
    const MARKET = 'market';
    const TRANSPORT = 'transport';
    const TRANSFER = 'transfer';
    const SPORT = 'sport';
    const ELECTRONIC = 'electronic';
    const MEDICAL = 'medical';
    const BEAUTY = 'beauty';
    const PHARMACY = 'pharmacy';
    const ZOO = 'zoo';
    const DIGITAL_STORE = 'digital_store';
    const REPAIR = 'repair';
    const STATIONARY = 'stationary';
    const EDUCATION = 'education';
    const COMMUNICATION = 'mobile_communication';
    const OTHER = 'other';

    public static function create(FinanceForm $form): Finance
    {
        $finance = new static();

        $finance->edit($form);

        return $finance;
    }

    public function edit(FinanceForm $form)
    {
        $this->budget_category = $form->budget_category;
        $this->category = $form->category;
        $this->date = $form->date;
        $this->time = $form->time;
        $this->username = $form->username;
        $this->money = $form->money;
        $this->comment = $form->comment;
    }

    public static function tableName(): string
    {
        return 'finance';
    }

    public function rules(): array
    {
        return [
            [['date', 'budget_category', 'category', 'money'], 'required'],
            [['money'], 'double'],
            [['date', 'time'], 'string'],
            [['category'], 'in', 'range' => array_keys(CategoryHelper::getList())],
            [['budget_category'], 'in', 'range' => array_keys(CategoryBudgetHelper::getList())],
            [['username'], 'string', 'max' => 30],
            [['comment'], 'string', 'max' => 250],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'budget_category' => 'Категория бюджета',
            'category' => 'Категория',
            'date' => 'Дата операции',
            'time' => 'Время операции',
            'username' => 'Имя пользователя',
            'money' => 'Средства',
            'comment' => 'Комментарий',
        ];
    }
}
