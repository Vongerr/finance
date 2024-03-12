<?php

use app\components\View;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

$financeAll = 0;
$cashbackAll = 0;

foreach ($data as $month => $monthInfo) {
    $finance = $monthInfo['finance'];
    $cashback = $monthInfo['cashback'];
    $monthTitle = MonthHelper::getValue($month);

    $financeAll += $monthInfo['finance'];
    $cashbackAll += $monthInfo['cashback'];

    echo "<h3>$monthTitle</h3>";

    echo "<h4>Финансы: $finance</h4>";
    echo "<h4>Кешбэк: $cashback</h4>";
}

echo "<h3>Итого</h3>";

echo "<h4>Финансы: $financeAll</h4>";
echo "<h4>Кешбэк: $cashbackAll</h4>";