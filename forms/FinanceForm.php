<?php

namespace app\forms;

use app\entities\Finance;
use app\helpers\BankHelper;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use yii\base\Model;

class FinanceForm extends Model
{
    public $budget_category;

    public $category;

    public $date;

    public $time;

    public $username;

    public $money;

    public $bank;

    public $comment;

    public $exclusion;

    public function __construct(Finance $finance = null, array $config = [])
    {
        parent::__construct($config);

        if ($finance) {

            $this->budget_category = $finance->budget_category;
            $this->category = $finance->category;
            $this->date = $finance->date;
            $this->time = $finance->time;
            $this->username = $finance->username;
            $this->money = $finance->money;
            $this->bank = $finance->bank;
            $this->comment = $finance->comment;
            $this->exclusion = $finance->exclusion;
        } else {
            $this->exclusion = Finance::NO_EXCLUSION;
            $this->username = 'vonger';
            $this->date = date('Y-m-d');
        }
    }

    public function rules(): array
    {
        return [
            [['date', 'budget_category', 'category', 'money'], 'required'],
            [['money'], 'double'],
            ['date', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'date', 'timestampAttributeFormat' => 'php:Y-m-d'],
            [['time'], 'string', 'max' => 5],
            [['category'], 'in', 'range' => array_keys(CategoryHelper::getList())],
            [['budget_category'], 'in', 'range' => array_keys(CategoryBudgetHelper::getList())],
            [['bank'], 'in', 'range' => array_keys(BankHelper::getList())],
            [['username'], 'string', 'max' => 30],
            [['comment'], 'string', 'max' => 250],
            [['exclusion'], 'integer', 'max' => 2],
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
            'bank' => 'Банк',
            'comment' => 'Комментарий',
            'exclusion' => 'Исключение'
        ];
    }

    public function formName(): string
    {
        return 'statistic-form';
    }
}