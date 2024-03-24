<?php

use app\components\View;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

$financeAll = 0;
$cashbackAll = 0;

$qiwi = 9346;

$changeYear = 0; ?>

    <table>
        <caption>Финансы</caption>
        <tr>
            <th colspan="5">Год</th>
            <th colspan="15">Месяц</th>
            <th colspan="4">Деньги</th>
        </tr>

        <?php
        foreach ($data as $year => $yearInfo) {
            foreach ($yearInfo as $month => $monthInfo) {

                $finance = $monthInfo['finance'];
                $cashback = $monthInfo['cashback'];
                $monthTitle = MonthHelper::getValue($month);

                $style = $finance > 0 ? 'style="color: green;"' : 'style="color: red;"';

                ?>
                <tr>
                    <td colspan="5"><?= $changeYear != $year ? $year : '' ?></td>
                    <td colspan="15"><?= MonthHelper::getValue($month) ?></td>
                    <td colspan="4" <?php echo $style ?>><?= number_format((float)$finance, 2, '.', '') ?></td>
                </tr>
                <?php

                $financeAll += $monthInfo['finance'];
                $cashbackAll += $monthInfo['cashback'];

                if ($changeYear != $year) {

                    $changeYear = $year;
                }
            }
        }

        $financeAll -= $qiwi
        ?>
    </table>
<?php

echo "<h2>Итого</h2>";

echo "<h4>Финансы: $financeAll</h4>";
echo "<h4>Кешбэк: $cashbackAll</h4>";

$this->registerCss(
    <<<CSS
table {
border-spacing: 0 10px;
font-family: 'Open Sans', sans-serif;
font-weight: bold;
}
th {
padding: 10px 20px;
background: #56433D;
color: #F9C941;
border-right: 2px solid; 
font-size: 0.9em;
}
th:first-child {
text-align: left;
}
th:last-child {
border-right: none;
}
td {
vertical-align: middle;
padding: 10px;
font-size: 14px;
text-align: center;
border-top: 2px solid #56433D;
border-bottom: 2px solid #56433D;
border-right: 2px solid #56433D;
}
td:first-child {
border-left: 2px solid #56433D;
border-right: none;
}
td:nth-child(2){
text-align: left;
}
CSS

);