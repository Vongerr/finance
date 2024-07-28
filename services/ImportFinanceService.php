<?php

namespace app\services;

use app\entities\Finance;
use app\forms\FinanceForm;
use app\repositories\FinanceRepository;
use Exception;
use SpreadsheetReader;

class ImportFinanceService
{
    /**
     * @var FinanceRepository
     */
    private $repository;

    public function __construct(FinanceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function importFinanceOtkritie(): void
    {
        $exclusions = [
            'Даниил Ю.' => 'Даниил Ю.',
            'Перевод СБП A3326165907612280000020011120701 от Ирина Николаевна Ю 79061397375 Сбербанк' => 'Перевод СБП A3326165907612280000020011120701 от Ирина Николаевна Ю 79061397375 Сбербанк',
            'Перевод собств. ср-в со счёта 40817810700004657088 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в со счёта 40817810700004657088 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.',
            'VB24 D. 35V, UL. GEROE' => 'VB24 D. 35V, UL. GEROE',
            'Перевод с карты на счёт' => 'Перевод с карты на счёт',
            'Перевод СБП A32470754359952B0000090011060501 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк' => 'Перевод СБП A32470754359952B0000090011060501 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк',
            'Перевод СБП A32121906047931E0000090011040302 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк' => 'Перевод СБП A32121906047931E0000090011040302 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк',
            'Перевод СБП B31860930251550C0000150011020303 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк' => 'Перевод СБП B31860930251550C0000150011020303 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк',
            'Перевод СБП A3121204137289020000070010970701 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк' => 'Перевод СБП A3121204137289020000070010970701 от ДАНИИЛ ВЛАДИМИРОВИЧ Ю 79631265667 Тинькофф Банк',
            'Перевод СБП B30852000199320C000017B1ACA19C7D от Даниил Владимирович Ю 79631265667 ВТБ.' => 'Перевод СБП B30852000199320C000017B1ACA19C7D от Даниил Владимирович Ю 79631265667 ВТБ.',
            'Перевод СБП B30851958528810A000016B1ACA19C7D от Даниил Владимирович Ю 79631265667 ВТБ.' => 'Перевод СБП B30851958528810A000016B1ACA19C7D от Даниил Владимирович Ю 79631265667 ВТБ.',
            'Перевод СБП A30751240063490B00000962ADDB9DDF от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП A30751240063490B00000962ADDB9DDF от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод СБП A306517595300101000005461030F503 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП A306517595300101000005461030F503 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод СБП B305909361603803000018461030F503 от Даниил Владимирович Ю 79631265667 ВТБ.' => 'Перевод СБП B305909361603803000018461030F503 от Даниил Владимирович Ю 79631265667 ВТБ.',
            'Перевод СБП A3021084224669130000077B3DA2D4A7 от Даниил Владимирович Ю 79631265667 ВТБ.' => 'Перевод СБП A3021084224669130000077B3DA2D4A7 от Даниил Владимирович Ю 79631265667 ВТБ.',
            'Перевод СБП A3021074233769120000097B3DA2D4A7 от Ирина Николаевна Ю 79061397375 Сбербанк.' => 'Перевод СБП A3021074233769120000097B3DA2D4A7 от Ирина Николаевна Ю 79061397375 Сбербанк.',
            'Перевод по номеру телефона. Отправитель: Ирина Ю. Получатель: Даниил Ю.' => 'Перевод по номеру телефона. Отправитель: Ирина Ю. Получатель: Даниил Ю.',
            'Перевод СБП A235008185874216000005CE5685024C от Ирина Николаевна Ю 79061397375 ВТБ.' => 'Перевод СБП A235008185874216000005CE5685024C от Ирина Николаевна Ю 79061397375 ВТБ.',
            'Банкомат ВТБ 24' => 'Банкомат ВТБ 24',
            'Перевод СБП A2344140128122070000091288DBBF35 от Даниил Владимирович Ю 79631265667 ВТБ.' => 'Перевод СБП A2344140128122070000091288DBBF35 от Даниил Владимирович Ю 79631265667 ВТБ.',
            'Перевод СБП A22171321068802000000733E625FCB3 от Владимир Геннадьевич Ю 79093663481 Сбербанк.' => 'Перевод СБП A22171321068802000000733E625FCB3 от Владимир Геннадьевич Ю 79093663481 Сбербанк.',
            'Перевод СБП B2167045053687090000196517BE1DD5 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП B2167045053687090000196517BE1DD5 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод СБП B21241317427450B0000171D052E2502 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП B21241317427450B0000171D052E2502 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод СБП B21191253224990C0000151D052E2502 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП B21191253224990C0000151D052E2502 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810700004657088 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810700004657088 (Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810700004640587 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810700004640587 (Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810500004464727 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810500004464727 (Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в со счёта 40817810100003873007 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в со счёта 40817810100003873007 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.',
            'Банкомат' => 'Банкомат',
            'Перевод собств. ср-в со счёта 40817810326083159360 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в со счёта 40817810326083159360 (Юмаков Д. В.) на карту 2200********7072 (40817810880005424392 Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810900004367806 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********7072 (40817810880005424392 Юмаков Д. В.) на счёт 40817810900004367806 (Юмаков Д. В.). НДС не облагается.',
            'Газпромбанк' => 'Газпромбанк',
            'Перевод с карты на карту' => 'Перевод с карты на карту',
            'Альфа-Банк' => 'Альфа-Банк',
            'Перевод СБП A132109091085324000006211D142271 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП A132109091085324000006211D142271 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод СБП B12850656470530100001546241820D7 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.' => 'Перевод СБП B12850656470530100001546241820D7 от Даниил Владимирович Ю 79631265667 Тинькофф Банк.',
            'Перевод собств. ср-в со счёта 40817810100003873007 (Юмаков Д. В.) на карту 2200********9562 (40817810880005424392 Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в со счёта 40817810100003873007 (Юмаков Д. В.) на карту 2200********9562 (40817810880005424392 Юмаков Д. В.). НДС не облагается.',
            'Перевод по системе СБП. Отправитель: Даниил Владимирович Ю тел. 79631265667.' => 'Перевод по системе СБП. Отправитель: Даниил Владимирович Ю тел. 79631265667.',
            'Перевод по системе СБП. Отправитель: Ирина Николаевна Ю тел. 79061397375.' => 'Перевод по системе СБП. Отправитель: Ирина Николаевна Ю тел. 79061397375.',
            'Tinkoff Bank P2P' => 'Tinkoff Bank P2P',
            'Перевод собств. ср-в с карты 2200********9562 (40817810880005424392 Юмаков Д. В.) на счёт 40817810100003873007 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********9562 (40817810880005424392 Юмаков Д. В.) на счёт 40817810100003873007 (Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в со счёта 40817810000003571412 (Юмаков Д. В.) на карту 2200********9562 (40817810880005424392 Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в со счёта 40817810000003571412 (Юмаков Д. В.) на карту 2200********9562 (40817810880005424392 Юмаков Д. В.). НДС не облагается.',
            'Перевод собств. ср-в с карты 2200********9562 (40817810880005424392 Юмаков Д. В.) на счёт 40817810000003571412 (Юмаков Д. В.). НДС не облагается.' => 'Перевод собств. ср-в с карты 2200********9562 (40817810880005424392 Юмаков Д. В.) на счёт 40817810000003571412 (Юмаков Д. В.). НДС не облагается.',
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
        ];

        $transports = [
            'Перевод денежных средств' => 'Переводы',
            'Перевод между своими счетами' => 'Переводы',
            'Транспорт' => 'Общественный транспорт',
            'Платежи' => 'Мобильная связь',
            'Красота и здоровье' => 'Красота',
            'Рестораны и кафе' => 'Рестораны',
            'Дом' => 'Дом и ремонт',
            'Развлечения и шопинг' => 'Развлечения',
            'Бизнес услуги' => 'Другое',
            'Спорт' => 'Спорттовары'
        ];

        $dateScholarshipList = [
            '2022-07-25' => '2022-07-25',
            '2022-06-24' => '2022-06-25',
        ];

        $dateCashListExclusion = [
            '2022-01-19' => '2022-01-19',
            '2021-09-04' => '2021-09-04',
        ];

        $text = file_get_contents($this->getPathDocs('otkritie.txt'));

        $financeList = explode(';', $text);
        $indexPay = 0;

        $list = [];

        foreach ($financeList as $index => $finance) {

            if ($index < 11) continue;

            $list[$indexPay][] = $finance;

            if ($finance == 'RUR') ++$indexPay;
        }

        $categoryList = $this->repository->getCategoryList();
        $hashList = $this->repository->getFinanceList();

        /*$arr = [];

        foreach ($list as $it) {
            if (!isset($it[6])) continue;
            if ($it[3] == 'Ошибка') continue;

            $arr[$it[5] . '; ' . $it[6]] = $it[6];
        }

        printr($arr,1);*/

        $count = 0;

        foreach ($list as $name) {

            if (!isset($name[6])) continue;
            if ($name[2] == 'Ошибка') continue;

            $form = new FinanceForm();

            $category = $transports[$name[5]] ?? $name[5];

            $form->bank = Finance::OTKRITIE;
            $form->date = date('Y-m-d', strtotime($name[0]));
            $form->time = date('H:i', strtotime($name[0]));
            $form->comment = $name[6];
            $form->category = $categoryList[$category] ?? Finance::OTHER;
            $form->exclusion = isset($exclusions[$name[6]]) ? Finance::EXCLUSION : Finance::NO_EXCLUSION;

            if ($name[6] == 'Перевод между своими счетами')
                $form->exclusion = Finance::EXCLUSION;

            if (str_contains($name[6], 'заработной')
                || str_contains($name[6], 'Пособия')
                || str_contains($name[6], 'Salary')
                || str_contains($name[6], 'аванс')
                || str_contains($name[6], 'отпускные')) {

                $form->category = Finance::SALARY;
            }

            if (str_contains($name[6], 'стипендия')) {

                $form->category = Finance::SCHOLARSHIP;
            }

            if (date('Y-m-d', strtotime($name[0])) < '2022-03-01' && Finance::SALARY == $categoryList[$category]
                || date('Y-m-d', strtotime($name[0])) < '2022-03-01' && str_contains($name[6], 'реестру')
                || in_array(date('Y-m-d', strtotime($name[0])), $dateScholarshipList) && Finance::SALARY == $categoryList[$category]) {

                $form->category = Finance::SCHOLARSHIP;
            }

            $indexMoney = (count($name) == 9) ? 7 : 9;

            $form->budget_category = $name[$indexMoney] > 0 ? Finance::REVENUE : Finance::EXPENSES;
            $form->money = $name[$indexMoney] > 0 ? (double)$name[$indexMoney] : (double)$name[$indexMoney] * (-1);

            if (Finance::CASH == $form->category && Finance::EXPENSES == $form->budget_category
                || $name[6] == 'Перевод между своими счетами'
                || in_array(date('Y-m-d', strtotime($name[0])), $dateCashListExclusion)) $form->exclusion = Finance::EXCLUSION;

            if (isset($hashList[$this->repository->getHashFinance($form)])) continue;

            $model = Finance::create($form);

            $this->repository->save($model);

            ++$count;
        }

        echo 'Все финансы импортированы: ' . $count;
    }

    /**
     * @throws Exception
     */
    public function importFinanceAlpha(): void
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
                if (!$row[0] && !$row[7]) continue;

                $form = new FinanceForm();

                $category = $transports[$row[10]] ?? $row[10];

                $form->bank = Finance::ALFA;
                $form->date = date('Y-m-d', strtotime($row[0]));
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
    public function importFinance(): void
    {
        $reader = new SpreadsheetReader($this->getPathDocs('finance.xlsx'));

        $sheets = $reader->Sheets();

        $count = 0;

        $arr = [];

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;
                if ($row[2] == 'FAILED') continue;

                $arr[$row[5]] = $row[5];
            }
        }

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;

                $model = Finance::createImport();

                $model->hash = $row[0];
                $model->budget_category = $row[1];
                $model->category = $row[2];
                $model->date = date('Y-m-d', strtotime($row[5]));
                $model->time = $row[4];
                $model->date_time = $row[5];
                $model->username = $row[6];
                $model->money = $row[7];
                $model->bank = $row[8];
                $model->comment = $row[9];
                $model->exclusion = $row[10];
                $model->created_at = date('Y-m-d H:i', strtotime($row[11]));
                $model->updated_at = $row[12] ? date('Y-m-d H:i', strtotime($row[12])) : null;

                $this->repository->save($model);

                ++$count;
            }
        }
        echo 'Все финансы импортированы: ' . $count;
    }

    /**
     * @throws Exception
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
        ];

        $reader = new SpreadsheetReader($this->getPathDocs('tinkoff.xlsx'));

        $sheets = $reader->Sheets();

        $categoryList = $this->repository->getCategoryList();
        $hashList = $this->repository->getFinanceList();

        $count = 0;

        $arr = [];

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;
                if ($row[2] == 'FAILED') continue;

                $arr[$row[5]] = $row[5];
            }
        }

        foreach ($sheets as $index => $name) {

            $reader->ChangeSheet($index);

            foreach ($reader as $indexRow => $row) {

                if ($indexRow == 0) continue;
                if ($row[2] == 'FAILED') continue;

                $form = new FinanceForm();

                $category = $transports[$row[9]] ?? $row[9];

                $form->bank = Finance::TINKOFF;
                $form->date = date('Y-m-d', strtotime($row[0]));
                $form->time = date('H:i', strtotime($row[0]));
                $form->budget_category = (int)$row[4] > 0 ? Finance::REVENUE : Finance::EXPENSES;
                $form->category = $categoryList[$category] ?? Finance::OTHER;
                $form->money = (double)$row[14];
                $form->comment = $row[11];
                $form->exclusion = isset($exclusions[$row[11]]) ? Finance::EXCLUSION : Finance::NO_EXCLUSION;

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