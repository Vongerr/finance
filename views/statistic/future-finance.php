<?php

use app\components\View;

/* @var $this View */
/* @var $data array */

$money = 230000; // начальный капитал

$invest_month = 45000; // плата в месяц

$percent = 0.16 / 12;

$period = 4 * 41;

$tax = 0.87; // налог

$inflation = 0.93;

for ($i = 0; $i < $period; $i++) {

    $base = $invest_month * $percent * $tax;

    $money += $invest_month + $base * 6;

    $money += $money * $percent * 3 * $tax;

    if (($i % 4) == 0) $money = $money * $inflation;
}

echo '<h2>' . intdiv($money, 1) . ' </h2>';
echo '<h2>' . intdiv($money, 1000000) . ' миллионов</h2>';
echo '<h2>' . intdiv($money - intdiv($money, 1000000) * 1000000, 1000) . ' тысяч</h2>';
echo '<h2>' . intdiv($money - intdiv($money, 1000) * 1000, 1) . ' рублей</h2>';