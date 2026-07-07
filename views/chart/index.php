<?php

use yii\helpers\Html;

/** @var array $chartData */
/** @var array $years */
/** @var string $selectedYear */
/** @var string|null $selectedMonth */
/** @var array $categories */
/** @var string|null $selectedCategory */

?>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <form class="d-flex flex-wrap align-items-center gap-2" method="get">
            <select name="year" class="form-select form-select-sm" style="width: auto;">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>

            <select name="month" class="form-select form-select-sm" style="width: auto;">
                <option value="">Все месяцы</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>

            <select name="category" class="form-select form-select-sm" style="width: auto;">
                <option value="">Все категории</option>
                <?php foreach ($categories as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $key === $selectedCategory ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-sm btn-primary">Показать</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Распределение расходов по категориям</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="350"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Детализация</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Категория</th>
                            <th class="text-end">Сумма</th>
                            <th class="text-end">%</th>
                            <th class="text-end">Кол-во</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $firstColors = $chartData['colors']; ?>
                        <?php foreach ($chartData['rows'] as $i => $row): ?>
                            <tr>
                                <td>
                                    <span class="d-inline-block rounded-circle me-1"
                                          style="width:10px;height:10px;background:<?= $chartData['colors'][$i] ?? '#ccc' ?>"></span>
                                    <?= Html::encode($row['category']) ?>
                                </td>
                                <td class="text-end"><?= number_format($row['total'], 2, ',', ' ') ?></td>
                                <td class="text-end"><?= $row['percent'] ?>%</td>
                                <td class="text-end"><?= $row['count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($chartData['totalSum'] > 0): ?>
                            <tr class="fw-bold table-light">
                                <td>Итого</td>
                                <td class="text-end"><?= number_format($chartData['totalSum'], 2, ',', ' ') ?></td>
                                <td class="text-end">100%</td>
                                <td class="text-end"><?= array_sum(array_column($chartData['rows'], 'count')) ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
$labelsJson = json_encode($chartData['labels']);
$dataJson = json_encode($chartData['values']);
$colorsJson = json_encode($chartData['colors']);

$this->registerJs(<<<JS
(function() {
    var ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    var labels = $labelsJson;
    var data = $dataJson;
    var colors = $colorsJson;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 12,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                            var val = ctx.parsed;
                            var pct = total > 0 ? (val / total * 100).toFixed(1) : 0;
                            return ctx.label + ': ' + val.toLocaleString('ru-RU', {minimumFractionDigits: 2}) + ' руб. (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
})();
JS
);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [
    'position' => $this::POS_HEAD,
]);
