<?php

namespace app\entities;

use app\forms\FinanceForm;
use app\helpers\CategoryAllHelper;
use app\helpers\CategoryBudgetHelper;
use app\helpers\CategoryHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "attendance__absenteeism_periods".
 *
 * @property int $id
 * @property int $hash Хэш
 * @property string $budget_category Категория бюджета
 * @property string $category Категория
 * @property string $date Дата операции
 * @property string $time Время операции
 * @property string $date_time Время операции
 * @property string $username Имя пользователя
 * @property float $money Средства
 * @property float $bank Банк
 * @property float $comment Комментарий
 * @property float $exclusion Исключения
 * @property string $created_at
 * @property string $updated_at
 */
class Finance extends ActiveRecord
{
    const REVENUE = 'revenue'; // Доходы
    const EXPENSES = 'expenses'; // Расходы

    const EXCLUSION = 1; // Расходы
    const NO_EXCLUSION = 0; // Расходы

    const TAXI = 'taxi';
    const CAFE = 'cafe';
    const RESTAURANT = 'restaurant';
    const FAST_FOOD = 'fast_food';
    const MARKET = 'market';
    const PRODUCTS = 'products';
    const TRANSPORT = 'transport';
    const SALARY = 'salary';
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
    const BONUS = 'bonus';
    const MARKETPLACE = 'marketplace';
    const FUEL = 'fuel';
    const LOTTERIES = 'lotteries';
    const BOOKS = 'books';
    const SOUVENIRS = 'souvenirs';
    const ENTERTAINMENTS = 'entertainments';
    const KIDS = 'kids';
    const COSMETICS = 'cosmetics';
    const CLOTHES_AND_SHOES = 'clothes_and_shoes';
    const GOV_SERVICE = 'gov_service';
    const TRAIN_TICKET = 'train_ticket';
    const BANK_SERVICE = 'bank_service';
    const CASH = 'cash';
    const HOTELS = 'hotels';
    const SERVICE = 'service';
    const FINANCE = 'finance';
    const CHARITY = 'charity';
    const AUTO = 'auto';
    const REFUND = 'refund';
    const OTHER = 'other';

    const TINKOFF = 'tinkoff';
    const ALFA = 'alfa';
    const OTKRITIE = 'otkritie';
    const VTB = 'vtb';

    public static function create(FinanceForm $form): Finance
    {
        $finance = new static();

        $finance->edit($form);

        return $finance;
    }

    public function edit(FinanceForm $form): void
    {
        $this->hash = md5(
            $form->budget_category
            . $form->category
            . $form->date
            . $form->time
            . $form->money
        );
        $this->budget_category = $form->budget_category;
        $this->category = $form->category;
        $this->date = $form->date;
        $this->time = $form->time;
        $this->date_time = $form->date . ' ' . $form->time;
        $this->username = $form->username;
        $this->money = $form->money;
        $this->bank = $form->bank;
        $this->comment = $form->comment;
        $this->exclusion = $form->exclusion;
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
            [['date', 'time', 'date_time'], 'string'],
            [['category'], 'in', 'range' => array_keys(CategoryAllHelper::getList())],
            [['budget_category'], 'in', 'range' => array_keys(CategoryBudgetHelper::getList())],
            [['username'], 'string', 'max' => 30],
            [['comment'], 'string', 'max' => 250],
            [['exclusion'], 'integer', 'max' => 2],
            [['hash'], 'string', 'min' => 32, 'max' => 32],
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
            'date_time' => 'Дата и время операции',
            'username' => 'Имя пользователя',
            'money' => 'Средства',
            'bank' => 'Банк',
            'comment' => 'Комментарий',
            'exclusion' => 'Исключения',
        ];
    }
}
