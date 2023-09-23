<?php

namespace app\forms;

use app\entities\Finance;
use yii\base\Model;

class FinanceForm extends Model
{
    public $budget_category;

    public $category;

    public $date;

    public $time;

    public $username;

    public $money;

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
        } else {
            $this->username = 'vonger';
            $this->date = date('Y-m-d');
        }
    }

    public function rules(): array
    {
        return [
            [['date', 'budget_category', 'category', 'money'], 'required'],
            [['money'], 'double'],
            [['date', 'time'], 'safe'],
            [['category', 'budget_category'], 'string', 'max' => 20],
            [['username'], 'string', 'max' => 30],
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
            'money' => 'Средства'
        ];
    }

    public function formName(): string
    {
        return 'statistic-form';
    }
}