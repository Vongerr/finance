<?php

use app\components\View;
use app\entities\Finance;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */

$financeAll = 0;
$cashbackAll = 0;

$changeYear = 0;

foreach ($data as $year => $yearInfo) : ?>
    <label>
        <h2> <?= $year ?> </h2>
        <table class="col-sm-3 table-finance">
            <tr>
                <th class="th-finance">Месяц</th>
                <th class="th-finance">Деньги</th>
            </tr>
            <?php foreach ($yearInfo as $month => $monthInfo) :

                $finance = $monthInfo['finance'];
                $cashback = $monthInfo['cashback'];

                $monthTitle = MonthHelper::getValue($month);

                $style = $finance > 0 ? 'style="color: green;"' : 'style="color: red;"'; ?>
                <tr>
                    <td class="td-finance"><?= MonthHelper::getValue($month) ?></td>
                    <td class="td-finance" <?php echo $style ?>><?= number_format((float)$finance, 2, '.', '') ?></td>
                </tr>
                <?php $financeAll += $monthInfo['finance'];
                $cashbackAll += $monthInfo['cashback'];

                if ($changeYear != $year) {

                    $changeYear = $year;
                }
            endforeach;
            ?> </table>
    </label>
<?php endforeach;

$financeAll -= Finance::QIWI; ?>
    <label>
        <h2>Итого:</h2>
        <table class="col-sm-3 table-finance">
            <tr>
                <th class="th-finance" colspan="15">Финансы</th>
                <th class="th-finance" colspan="4">Кешбэк</th>
            </tr>
            <tr>
                <td class="td-finance" colspan="15"> <?= $financeAll ?></td>
                <td class="td-finance" colspan="4"> <?= $cashbackAll ?></td>
            </tr>
        </table>
    </label>
<?php

$this->registerCss(
    <<<CSS
.table-finance {
border-spacing: 0 10px;
font-family: 'Open Sans', sans-serif;
font-weight: bold;
}

.th-finance {
padding: 10px 20px;
background: #56433D;
color: #F9C941;
font-size: 0.9em;
border-top: 2px solid #56433D;
border-bottom: 2px solid #56433D;
border-right: 2px solid #56433D;
border-left: 2px solid #56433D;
}

.th-finance:first-child {
text-align: left;
}

.th-finance:last-child {
border-right: none;
}

.td-finance {
vertical-align: middle;
padding: 10px;
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