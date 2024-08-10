<?php

use app\components\View;
use app\entities\Finance;
use app\helpers\CategoryAllHelper;

/* @var $this View */
/* @var $data array */

/* foreach ($yearInfo as $month => $monthInfo) {
       echo '<th class="th-finance">' . MonthHelper::getValue($month) .'</th>';
}*/

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

            <?php
            foreach (Finance::CATEGORY_LIST as $category) :?>
                <tr>
                    <td class="td-finance"><?= CategoryAllHelper::getValue($category) ?></td>

                    <?php foreach ($yearInfo as $month => $monthInfo) :

                        if (isset($monthInfo[$category])) :?>

                            <td class="td-finance"><?= number_format((float)$monthInfo[$category], 0, ',', '.') ?></td>
                        <?php else: ?>

                            <td class="td-finance"> 0</td>
                        <?php endif; ?>

                        <?php if ($changeYear != $year) {

                        $changeYear = $year;
                    }
                    endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </label>

<?php endforeach;

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