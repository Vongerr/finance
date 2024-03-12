<?php

use app\components\View;

/* @var $this View */
/* @var $data array */

$base = 200000; // начальный капитал

$pay_month = 25000; // плата в месяц

$percent = 0.13 / 12;

$period = 4 * 41;

$nalog = 0.87;

$inflyacia = 0.93;

for ($i = 0; $i < $period; $i++) {

    $base += $pay_month + $pay_month * $percent * 3 * $nalog;

    $base += $pay_month + $pay_month * $percent * 2 * $nalog;

    $base += $pay_month + $pay_month * $percent * $nalog;

    $base += $base * $percent * 3 * $nalog;

    //if (($i % 4) == 0) $base = $base * $inflyacia;
}

echo '<h2>' . intdiv($base, 1) . ' </h2>';
echo '<h2>' . intdiv($base, 1000000) . ' миллионов</h2>';
echo '<h2>' . intdiv($base - intdiv($base, 1000000) * 1000000, 1000) . ' тысяч</h2>';
echo '<h2>' . intdiv($base - intdiv($base, 1000) * 1000, 1) . ' рублей</h2>';