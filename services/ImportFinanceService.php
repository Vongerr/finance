<?php

namespace app\services;

use SpreadsheetReader;

class ImportFinanceService
{
    public function importFinance(): void
    {
        $exclusion = [
            'Даниил Ю.',
            'Ирина Ю.',
            'Владимир Ю.',
            'Перевод между счетами',
            'Вывод с брокерского счета',
            'Дмитрий Ю.',
            'Артем Г.',
            'Регулярный перевод в Инвесткопилку',
            'Вывод со счета Тинькофф Брокер',
            'Вывод средств с брокерского счета',
            'Пополнение счета Тинькофф Брокер',
            'Пополнение Инвесткопилки',
            'Перевод на вклад',
        ];
        $arr = [];
        $reader = new SpreadsheetReader('C:\Users\danii\Desktop/all_time_tinkoff.xlsx');
        $sheets = $reader->Sheets();

        foreach ($sheets as $index => $name) {
            echo 'Sheet #' . $index . ': ' . $name;

            $reader->ChangeSheet($index);

            foreach ($reader as $row) {
                $arr[$row[5]] = $row[5];
            }
        }
        printr($arr, 1);
    }
}