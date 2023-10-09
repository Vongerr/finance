<?php

use app\components\View;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

foreach ($data as $month => $monthInfo) {
    $finance = $monthInfo['finance'];
    $cashback = $monthInfo['cashback'];
    $monthTitle = MonthHelper::getValue($month);

    echo "<h3>$monthTitle</h3>";

    echo "<h4>Финансы: $finance</h4>";
    echo "<h4>Кешбэк: $cashback</h4>";
}