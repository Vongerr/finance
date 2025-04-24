<?php

use app\components\View;
use app\helpers\CategoryAllHelper;
use app\helpers\MonthHelper;

/* @var $this View */
/* @var $data array */
/* @var $categoryList array */

$changeYear = 0;

$color = 'style="color: #931517;"';

foreach ($data as $year => $yearInfo) : ?>

    <label>
        <h2> <?= $year ?> </h2>
        <table class="col-sm-3 table-finance">
            <tr>
                <th class="th-finance">Категория</th>
                <?php foreach ($yearInfo as $month => $monthInfo) : ?>
                    <th class="th-finance"><?= MonthHelper::getValue($month) ?></th>
                <?php endforeach; ?>
                <th class="th-finance">Всего</th>
            </tr>

            <?php foreach ($categoryList[$year] as $category => $item) : ?>
                <tr>
                    <td class="td-finance"><?= CategoryAllHelper::getValue($category) ?></td>

                    <?php foreach ($yearInfo as $month => $monthInfo) : ?>

                        <td class="td-finance"><?= isset($monthInfo[$category])
                                ? number_format((float)$monthInfo[$category], 0, ',', '.')
                                : '' ?></td>

                        <?php if ($changeYear != $year) {

                            $changeYear = $year;
                        }
                    endforeach; ?>
                    <td class="td-finance"><?= number_format((float)$item, 0, ',', '.') ?></td>
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