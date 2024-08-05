<?php

use app\components\View;
use app\entities\Finance;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

$salaryAll = 0;
$scholarshipAll = 0;
$revenueAll = 0;
$expensesAll = 0;
$financeAll = 0;

$changeYear = 0;

foreach ($data as $year => $yearInfo) : ?>

    <label>
        <h2> <?= $year ?> </h2>
        <table class="col-sm-3 table-finance">
            <tr>
                <th class="th-finance">Категория</th>
                <th class="th-finance">Январь</th>
                <th class="th-finance">Февраль</th>
                <th class="th-finance">Март</th>
                <th class="th-finance">Апрель</th>
                <th class="th-finance">Май</th>
                <th class="th-finance">Июнь</th>
                <th class="th-finance">Июль</th>
                <th class="th-finance">Август</th>
                <th class="th-finance">Сентябрь</th>
                <th class="th-finance">Октябрь</th>
                <th class="th-finance">Ноябрь</th>
                <th class="th-finance">Декабрь</th>
                <th class="th-finance">Всего</th>
            </tr>

            <?php foreach ($yearInfo as $month => $monthInfo) :

                $salary = $monthInfo['salary'];
                $scholarship = $monthInfo['scholarship'];
                $revenue = $monthInfo['revenue'];
                $expenses = $monthInfo['expenses'];
                $finance = $monthInfo['finance'];

                $monthTitle = MonthHelper::getValue($month);

                $style = $finance > 0 ? 'style="color: green;"' : 'style="color: red;"'; ?>
                <tr>
                    <td class="td-finance"><?= MonthHelper::getValue($month) ?></td>
                    <td class="td-finance"><?= number_format((float)$salary, 0, ',', '.') ?></td>
                    <td class="td-finance"><?= number_format((float)$scholarship, 0, ',', '.') ?></td>
                    <td class="td-finance"><?= number_format((float)$revenue, 0, ',', '.') ?></td>
                    <td class="td-finance"><?= number_format((float)$expenses, 0, ',', '.') ?></td>
                    <td class="td-finance" <?php echo $style ?>><?= number_format((float)$finance, 0, ',', '.') ?></td>
                </tr>
                <?php
                $salaryAll += $monthInfo['salary'];
                $scholarshipAll += $monthInfo['scholarship'];
                $revenueAll += $monthInfo['revenue'];
                $expensesAll += $monthInfo['expenses'];
                $financeAll += $monthInfo['finance'];

                if ($changeYear != $year) {

                    $changeYear = $year;
                }
            endforeach; ?> </table>
    </label>

<?php endforeach;
$financeAll -= Finance::QIWI;
$expensesAll -= Finance::QIWI; ?>

    <label>
        <h2>Итого:</h2>
        <table class="col-sm-3 table-finance">
            <tr>
                <th class="th-finance" colspan="15">Зарплата</th>
                <th class="th-finance" colspan="15">Стипендия</th>
                <th class="th-finance" colspan="15">Доходы</th>
                <th class="th-finance" colspan="15">Расходы</th>
                <th class="th-finance" colspan="15">Итого</th>
            </tr>
            <tr>
                <td class="td-finance" colspan="15"> <?= financeView((float)$salaryAll) ?></td>
                <td class="td-finance" colspan="15"> <?= financeView((float)$scholarshipAll) ?></td>
                <td class="td-finance" colspan="15"> <?= financeView((float)$revenueAll) ?></td>
                <td class="td-finance" colspan="15"> <?= financeView((float)$expensesAll) ?></td>
                <td class="td-finance" colspan="15"> <?= financeView((float)$financeAll) ?></td>
            </tr>
        </table>
    </label>
<?php
function financeView(float $money): string
{
    return number_format($money, 0, ',', '.');
}

$this->registerCss(
    <<<CSS
.table-finance {
border-spacing: 0 10px;
font-family: 'Open Sans', sans-serif;
font-weight: bold;
}

.th-finance {
padding: 5px 5px;
background: #56433D;
color: #F9C941;
font-size: 0.9em;
border-top: 2px solid #56433D;
border-bottom: 2px solid #56433D;
border-right: 2px solid #56433D;
border-left: 2px solid #56433D;
}

.th-finance:first-child {
text-align: center;
}

.th-finance:last-child {
border-right: none;
}

.td-finance {
vertical-align: middle;
padding: 5px;
font-size: 14px;
text-align: center;
border-top: 2px solid #56433D;
border-bottom: 2px solid #56433D;
border-right: 2px solid #56433D;
border-left: 2px solid #56433D;
}

.td-finance:first-child {
border-left: 2px solid #56433D;
border-right: none;
}

.td-finance:nth-child(2){
text-align: left;
}

CSS
);