<?php

namespace app\services;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\repositories\FinanceRepository;
use Exception;
use SpreadsheetReader;
use Throwable;

class ImportFinanceService
{
    private FinanceRepository $repository;

    public function __construct(FinanceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function importFinanceAlpha(): void
    {
        $exclusions = [
            'Даниил Ю.' => 'Даниил Ю.',
            'Даниил Владимирович Ю' => 'Даниил Владимирович Ю',
            //'Ирина Ю.' => 'Ирина Ю.',
            //'Владимир Ю.' => 'Владимир Ю.',
            'Перевод между счетами' => 'Перевод между счетами',
            'Вывод с брокерского счета' => 'Вывод с брокерского счета',
            //'Дмитрий Ю.' => 'Дмитрий Ю.',
            'Артем Г.' => 'Артем Г.',
            'Регулярный перевод в Инвесткопилку' => 'Регулярный перевод в Инвесткопилку',
            'Вывод средств с брокерского счета' => 'Вывод средств с брокерского счета',
            'Пополнение брокерского счета' => 'Пополнение брокерского счета',
            'Пополнение Инвесткопилки' => 'Пополнение Инвесткопилки',
            'Перевод на вклад' => 'Перевод на вклад',
            'Между своими счетами' => 'Между своими счетами',
        ];

        $transports = [
            'Транспорт' => 'Общественный транспорт',
            'Местный транспорт' => 'Общественный транспорт',
            'Животные' => 'Зоомагазин',
            'Цифровые товары' => 'Онлайн покупки',
            'Кино' => 'Развлечения',
            'Связь' => 'Мобильная связь',
            'Различные товары' => 'Другое',
            'Пополнения' => 'Переводы',
            'Финансовые операции' => 'Финансы',
            'Прочие расходы' => 'Другое',
            '' => 'Другое',
        ];

        $reader = new SpreadsheetReader($this->getPathDocs('alpha.xlsx'));
        $sheets = $reader->Sheets();

        $categoryList = $this->repository->getCategoryList();
        $hashList = $this->repository->getFinanceList();

        $count = 0;

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;
                if (count($row) < 13) continue;
                if (!$row[0] && !$row[7]) continue;

                $form = new FinanceForm();

                $category = $transports[$row[10]] ?? $row[10];

                $form->bank = Finance::ALFA;
                $form->date = isset($row[0]) ? date('Y-m-d', strtotime($row[0])) : '';
                $form->budget_category = $row[12] == 'Пополнение' ? Finance::REVENUE : Finance::EXPENSES;
                $form->category = $categoryList[$category] ?? Finance::OTHER;
                $form->time = '01:00';
                $form->money = (double)$row[7];
                $form->comment = $row[6];
                $form->exclusion = isset($exclusions[$row[6]]) ? Finance::EXCLUSION : Finance::NO_EXCLUSION;

                if (isset($hashList[$this->repository->getHashFinance($form)])) continue;

                $model = Finance::create($form);

                $this->repository->save($model);

                ++$count;
            }
        }

        echo 'Все финансы импортированы: ' . $count;
    }

    /**
     * @throws Exception
     */
    public function importFinanceVtb(): void
    {
        $exclusions = [
            'Даниил Ю.' => 'Даниил Ю.',
            'Даниил Владимирович Ю' => 'Даниил Владимирович Ю',
            //'Ирина Ю.' => 'Ирина Ю.',
            //'Владимир Ю.' => 'Владимир Ю.',
            'Перевод между счетами' => 'Перевод между счетами',
            'Вывод с брокерского счета' => 'Вывод с брокерского счета',
            'Регулярный перевод в Инвесткопилку' => 'Регулярный перевод в Инвесткопилку',
            'Вывод средств с брокерского счета' => 'Вывод средств с брокерского счета',
            'Пополнение брокерского счета' => 'Пополнение брокерского счета',
            'Пополнение Инвесткопилки' => 'Пополнение Инвесткопилки',
            'Перевод на вклад' => 'Перевод на вклад',
            'Между своими счетами' => 'Между своими счетами',
            'Между счетами' => 'Между своими счетами',
            'Операция отклонена' => 'Операция отклонена',
        ];

        $transports = [
            'Между счетами' => 'Переводы',
            'Переводы людям' => 'Переводы',
            'Входящие переводы' => 'Переводы',
            'Кафе и рестораны' => 'Рестораны',
            'Услуги' => 'Сервис',
            'АЗС' => 'Топливо',
            'Алкоголь' => 'Супермаркеты',
            'Цифровой контент' => 'Онлайн покупки',
            'Электроника' => 'Электроника и техника',
            'Зоотовары' => 'Зоомагазин',
            'Театры и кино' => 'Развлечения',
            'Платные дороги' => 'Сервис',
            'Спортивные товары' => 'Спорттовары',
            'Услуги связи' => 'Мобильная связь',
            'Сотовая связь' => 'Мобильная связь',
            'Государственные услуги' => 'Госуслуги',
            'Здоровье' => 'Медицина',
            'Фитнес' => 'Медицина',
            'Транспорт' => 'Общественный транспорт',
            'Местный транспорт' => 'Общественный транспорт',
            'Животные' => 'Зоомагазин',
            'Цифровые товары' => 'Онлайн покупки',
            'Кино' => 'Развлечения',
            'Турагентства' => 'Развлечения',
            'Связь' => 'Мобильная связь',
            'Различные товары' => 'Другое',
            'Пополнения' => 'Переводы',
            'Финансовые операции' => 'Финансы',
            'Кешбэк' => 'Бонусы',
            'Цветы' => 'Другое',
            'Прочие расходы' => 'Другое',
            'Другие расходы' => 'Другое',
            'Другие услуги' => 'Другое',
            'Другие зачисления' => 'Другое',
            'Авиабилеты' => 'Другое',
            '' => 'Другое',
        ];

        $reader = new SpreadsheetReader($this->getPathDocs('vtb.csv'));

        $sheets = $reader->Sheets();

        $categoryList = $this->repository->getCategoryList();
        $hashList = $this->repository->getFinanceList();

        $count = 0;

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;

                if (count($row) < 13) continue;
                if (!$row[3] && !$row[7]) continue;

                $form = new FinanceForm();

                $category = $transports[$row[13]] ?? $row[13];

                $money = (double)$row[7] ?? 0;

                $form->bank = Finance::VTB;
                $form->date = isset($row[3]) ? date('Y-m-d', strtotime($row[3])) : '';
                $form->budget_category = $row[4] == 'Списание' ? Finance::EXPENSES : Finance::REVENUE;
                $form->category = $categoryList[$category] ?? Finance::OTHER;
                $form->time = isset($row[3]) ? date('H:i', strtotime($row[3])) : '';
                $form->money = $money < 0 ? $money * (-1) : $money;
                $form->comment = $row[11];
                $form->exclusion = (isset($exclusions[$row[11]]) || isset($exclusions[$row[13]]))
                    ? Finance::EXCLUSION
                    : Finance::NO_EXCLUSION;

                if (isset($hashList[$this->repository->getHashFinance($form)])) continue;

                $model = Finance::create($form);

                $this->repository->save($model);

                ++$count;
            }
        }

        echo 'Все финансы импортированы: ' . $count;
    }

    /**
     * @throws Exception
     */
    public function importFinance(): void
    {
        $reader = new SpreadsheetReader($this->getPathDocs('finance.xlsx'));

        $sheets = $reader->Sheets();

        $count = 0;

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;

                $model = Finance::createImport();

                $model->hash = $row[1];
                $model->budget_category = $row[2];
                $model->category = $row[3];
                $model->date = date('Y-m-d', strtotime($row[6]));
                $model->time = $row[5];
                $model->date_time = $row[6];
                $model->username = $row[7];
                $model->money = $row[8];
                $model->bank = $row[9];
                $model->comment = $row[10];
                $model->exclusion = $row[11];
                $model->created_at = date('Y-m-d H:i', strtotime($row[12]));
                $model->updated_at = $row[13] ? date('Y-m-d H:i', strtotime($row[13])) : null;

                $this->repository->save($model);

                ++$count;
            }
        }
        echo 'Все финансы импортированы: ' . $count;
    }

    /**
     * @throws Throwable
     */
    public function importFinanceTinkoff(): void
    {


        $exclusions = [
            'Даниил Ю.' => 'Даниил Ю.',
            //'Ирина Ю.' => 'Ирина Ю.',
            //'Владимир Ю.' => 'Владимир Ю.',
            'Перевод между счетами' => 'Перевод между счетами',
            'Вывод с брокерского счета' => 'Вывод с брокерского счета',
            //'Дмитрий Ю.' => 'Дмитрий Ю.',
            'Артем Г.' => 'Артем Г.',
            'Регулярный перевод в Инвесткопилку' => 'Регулярный перевод в Инвесткопилку',
            'Вывод со счета Тинькофф Брокер' => 'Вывод со счета Тинькофф Брокер',
            'Вывод средств с брокерского счета' => 'Вывод средств с брокерского счета',
            'Пополнение счета Тинькофф Брокер' => 'Пополнение счета Тинькофф Брокер',
            'Пополнение брокерского счета' => 'Пополнение брокерского счета',
            'Пополнение Инвесткопилки' => 'Пополнение Инвесткопилки',
            'Перевод на вклад' => 'Перевод на вклад',
            'Внесение наличных через банкомат Тинькофф' => 'Внесение наличных через банкомат Тинькофф',
            'Между своими счетами' => 'Между своими счетами',
            'Вывод с Инвесткопилки' => 'Вывод с Инвесткопилки',
            'Atomyze' => 'Atomyze',
            'Пополнение смарт-счета' => 'Пополнение смарт-счета',
            'Банк ВТБ' => 'Банк ВТБ',
            'Покупка золота' => 'Покупка золота'
        ];

        $true_categories = [
            'Транспорт' => 'Общественный транспорт',
            'Местный транспорт' => 'Общественный транспорт',
            'Животные' => 'Зоомагазин',
            'Цифровые товары' => 'Онлайн покупки',
            'Кино' => 'Развлечения',
            'Связь' => 'Мобильная связь',
            'Различные товары' => 'Другое',
            'Пополнения' => 'Переводы',
            'Банк ВТБ' => 'Наличные',
            'Пополнение. VB24 IOSHKAR-OLA G RUS' => 'Наличные',
            'Внесение наличных через банкомат Т-Банк' => 'Наличные'
        ];

        $reader = new SpreadsheetReader($this->getPathDocs('tinkoff.xlsx'));

        $sheets = $reader->Sheets();

        $categoryList = $this->repository->getCategoryList();
        $hashList = $this->repository->getFinanceList();

        $count = 0;

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;
                if ($row[2] == 'FAILED') continue;

                $form = new FinanceForm();

                $category = $true_categories[$row[11]] ?? $true_categories[$row[9]] ?? $row[9];

                $date = str_replace('"."', '.', trim(stripslashes($row[0]), '"'));

                $money = (double)$row[14] ?? 0;

                $form->bank = Finance::TINKOFF;
                $form->date = date('Y-m-d', strtotime($date));
                $form->time = date('H:i', strtotime($date));
                $form->budget_category = (int)$row[4] > 0 ? Finance::REVENUE : Finance::EXPENSES;
                $form->category = $categoryList[$category] ?? Finance::OTHER;
                $form->money = $money < 0 ? $money * (-1) : $money;
                $form->comment = $row[11];
                $form->exclusion = isset($exclusions[$row[11]]) ? Finance::EXCLUSION : Finance::NO_EXCLUSION;

                if ($row[11] == 'Внесение наличных через банкомат Т-Банк') printr($row,1);

                if (isset($hashList[$this->repository->getHashFinance($form)])) continue;

                $model = Finance::create($form);

                $this->repository->save($model);

                ++$count;
            }
        }
        echo 'Все финансы импортированы: ' . $count;
    }

    private function getPathDocs(string $name): string
    {
        $pathList = explode('\\', __DIR__);

        $path = array_shift($pathList);

        foreach ($pathList as $item) {

            if ('services' == $item) continue;

            $path .= '/' . $item;
        }

        $path .= '/web/docs-statistics/' . $name;

        return $path;
    }
}