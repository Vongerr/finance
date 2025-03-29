<?php

use app\components\View;

/* @var $this View */
/* @var $data array */

$baseMoney = 0;

$money = $baseMoney; // начальный капитал

$invest_month = 10294; // плата в месяц

$percent = 0.20 / 12;

$period = 12 * 5;

$tax = 0.87; // налог

$inflation = 0.93;

$taxAll = 0;

for ($i = 1; $i <= $period; $i++) {

    $money = ($invest_month + $money) * (1 + $percent);

    $taxMoney = ($invest_month + $money) * ($percent) * (1 - $tax);

    $taxAll += $taxMoney;

    $money -= $taxMoney;

    //if (($i % 12) == 0) $money = $money * $inflation;
}

printr($money);
printr($invest_month * $period);
printr($taxAll);

$money = ($money - $invest_month * $period);

echo '<h2>' . intdiv($money, 1) . ' </h2>';
echo '<h2>' . intdiv($money, 1000000) . ' миллионов</h2>';
echo '<h2>' . intdiv($money - intdiv($money, 1000000) * 1000000, 1000) . ' тысяч</h2>';
echo '<h2>' . intdiv($money - intdiv($money, 1000) * 1000, 1) . ' рублей</h2>';

$beginSumma = 500000;
$subtractSumma = 86922.32;
$subtractSumma = 10294.02;

$summa = $beginSumma;

$percentMoney = 0;

$percent = 0.0167;

for ($i = 1; $i <= 60; $i++) {

    if ($i > 2) $percent = 0.0133;

    $percentMoney += $summa * $percent;

    $summa -= $subtractSumma;
}

printr($summa);
printr($percentMoney + $summa);

echo 'Без траты вклада';

$summa = $beginSumma;

$percentMoney = 0;

$percent = 0.0167;

for ($i = 1; $i <= 60; $i++) {

    if ($i > 2) $percent = 0.0133;

    $percentMoney += $summa * $percent;
}

printr($percentMoney);