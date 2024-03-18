<?php

use app\components\View;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

$financeAll = 0;
$cashbackAll = 0;

$changeYear = 0; ?>

    <table>
        <caption>Финансы</caption>
        <tr>
            <th colspan="5">Год </th>
            <th colspan="15">Месяц</th>
            <th colspan="4">Деньги</th>
        </tr>

        <?php
        foreach ($data as $year => $yearInfo) {
            foreach ($yearInfo as $month => $monthInfo) {

                $finance = $monthInfo['finance'];
                $cashback = $monthInfo['cashback'];
                $monthTitle = MonthHelper::getValue($month);

                ?>
                <tr>
                    <td colspan="5"><?= $changeYear != $year ? $year : ''?></td>
                    <td colspan="15"><?= MonthHelper::getValue($month) ?></td>
                    <td colspan="4"><?= $finance?></td>
                </tr>
                <?php

                $financeAll += $monthInfo['finance'];
                $cashbackAll += $monthInfo['cashback'];

                if ($changeYear != $year) {

                    $changeYear = $year;
                }
            }
        } ?>
    </table>
<?php

echo "<h2>Итого</h2>";

echo "<h4>Финансы: $financeAll</h4>";
echo "<h4>Кешбэк: $cashbackAll</h4>";