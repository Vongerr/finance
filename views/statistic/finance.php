<?php

use app\components\View;

/* @var $this View */
/* @var $data array */

$finance = $data['finance'];
$cashback = $data['cashback'];

echo "<h4>Финансы: $finance</h4>";
echo "<h4>Кешбэк: $cashback</h4>";