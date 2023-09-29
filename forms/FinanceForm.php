<?php

namespace app\forms;

use app\entities\Finance;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use yii\base\Model;

//"bower-asset/bootstrap": "^3.3",
//        "npm-asset/jquery": "^2.2"
class FinanceForm extends Model
{
    public $budget_category;

    public $category;

    public $date;

    public $time;

    public $username;

    public $money;

    public $comment;

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
            $this->comment = $finance->comment;
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
            'comment' => 'Комментарий'
        ];
    }

    public function formName(): string
    {
        return 'statistic-form';
    }
}