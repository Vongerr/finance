<?php

use app\components\View;
use app\helpers\MonthHelper;
use yii\helpers\Html;

/* @var $this View */
/* @var array $statistic */
/* @var array $years */

$days = $statistic['days'];
$summary = $statistic['summary'];
$year = $statistic['year'];
$month = $statistic['month'];
$monthName = $statistic['monthName'];

$fmt = static fn(float $value): string => number_format($value, 0, ',', '.');

$totalNet = $summary['totalNet'];
$avgDaily = $summary['activeDays'] > 0 ? $summary['totalExpenses'] / $summary['activeDays'] : 0;
$maxExpense = max($summary['maxDayExpense'], 1);

$this->params['breadcrumbs'][] = Html::encode($monthName . ' ' . $year);
?>

<div class="container-fluid py-3 ds-page">
    <!-- Фильтр по периоду -->
    <div class="ds-card ds-filter mb-4">
        <div class="ds-filter-inner">
            <form class="ds-filter-form" method="get">
                <label class="ds-filter-label">
                    <span>Год</span>
                    <select name="year" class="form-select form-select-sm">
                        <?php if (empty($years)) $years = [$year]; ?>
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="ds-filter-label">
                    <span>Месяц</span>
                    <select name="month" class="form-select form-select-sm">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= MonthHelper::getValue($m) ?></option>
                        <?php endfor; ?>
                    </select>
                </label>
                <button type="submit" class="btn btn-sm ds-btn-primary">Показать</button>
            </form>
            <div class="ds-period-title">
                <i class="bi bi-calendar3 me-1"></i><?= Html::encode($monthName) ?> <?= $year ?>
            </div>
        </div>
    </div>

    <!-- Сводка -->
    <div class="ds-summary mb-4">
        <div class="ds-summary-card ds-sum-card-green">
            <div class="ds-sum-icon"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="ds-sum-body">
                <div class="ds-sum-label">Доходы</div>
                <div class="ds-sum-value"><?= $fmt($summary['totalRevenue']) ?> <small>руб.</small></div>
            </div>
        </div>
        <div class="ds-summary-card ds-sum-card-red">
            <div class="ds-sum-icon"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="ds-sum-body">
                <div class="ds-sum-label">Расходы</div>
                <div class="ds-sum-value"><?= $fmt($summary['totalExpenses']) ?> <small>руб.</small></div>
            </div>
        </div>
        <div class="ds-summary-card <?= $totalNet >= 0 ? 'ds-sum-card-blue' : 'ds-sum-card-dark' ?>">
            <div class="ds-sum-icon"><i class="bi bi-wallet2"></i></div>
            <div class="ds-sum-body">
                <div class="ds-sum-label">Итог</div>
                <div class="ds-sum-value"><?= ($totalNet >= 0 ? '+ ' : '- ') . $fmt(abs($totalNet)) ?> <small>руб.</small></div>
            </div>
        </div>
        <div class="ds-summary-card ds-sum-card-gold">
            <div class="ds-sum-icon"><i class="bi bi-bar-chart-line"></i></div>
            <div class="ds-sum-body">
                <div class="ds-sum-label">Дней с операциями</div>
                <div class="ds-sum-value"><?= $summary['activeDays'] ?> <small>из <?= $statistic['daysInMonth'] ?></small></div>
            </div>
        </div>
        <div class="ds-summary-card ds-sum-card-violet">
            <div class="ds-sum-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="ds-sum-body">
                <div class="ds-sum-label">Средний расход / день</div>
                <div class="ds-sum-value"><?= $fmt($avgDaily) ?> <small>руб.</small></div>
            </div>
        </div>
    </div>

    <?php if ($summary['activeDays'] > 0): ?>
        <!-- График -->
        <div class="ds-card mb-4">
            <div class="ds-card-head">
                <h5 class="ds-card-title"><i class="bi bi-activity me-2"></i>Расходы / доходы по дням</h5>
                <span class="ds-card-badge"><?= Html::encode($monthName) ?> <?= $year ?></span>
            </div>
            <div class="ds-card-body">
                <canvas id="dsChart" height="300"></canvas>
            </div>
        </div>
    <?php endif; ?>

    <!-- Сравнение дней -->
    <div class="ds-card">
        <div class="ds-card-head">
            <h5 class="ds-card-title"><i class="bi bi-list-columns-reverse me-2"></i>Сравнение дней</h5>
            <div class="ds-legend">
                <span class="ds-legend-item"><i class="bi bi-circle-fill" style="color:#2e8b57"></i> Доходы</span>
                <span class="ds-legend-item"><i class="bi bi-circle-fill" style="color:#c0392b"></i> Расходы</span>
                <span class="ds-legend-item ds-max-net"><i class="bi bi-star-fill"></i> Лучший день</span>
                <span class="ds-legend-item ds-max-exp"><i class="bi bi-exclamation-triangle-fill"></i> Макс. расход</span>
            </div>
        </div>
        <div class="ds-card-body p-0">
            <div class="table-responsive">
                <table class="table ds-table align-middle mb-0">
                    <thead>
                    <tr>
                        <th class="ds-th-day">День</th>
                        <th class="text-center">Операций</th>
                        <th class="text-end">Доходы</th>
                        <th class="text-end">Расходы</th>
                        <th class="text-end">Итог</th>
                        <th class="text-end">Среднее на операцию</th>
                        <th>Топ категория</th>
                        <th style="min-width: 140px;">Уровень расходов</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $currentWeek = null;
                    $weekFirst = null;
                    $weekLast = null;
                    $weekCount = 0;
                    $weekRevenue = 0.0;
                    $weekExpenses = 0.0;
                    $weekNet = 0.0;
                    ?>
                    <?php foreach ($days as $info): ?>
                        <?php
                        $week = (int)date('W', strtotime($info['date']));
                        if ($currentWeek !== null && $week !== $currentWeek):
                            ?>
                            <tr class="ds-week-total">
                                <td colspan="2">
                                    <i class="bi bi-calendar-week me-1"></i>Неделя <?= $currentWeek ?>
                                    <span class="ds-week-range"><?= date('d.m', strtotime($weekFirst)) ?> – <?= date('d.m', strtotime($weekLast)) ?></span>
                                </td>
                                <td class="text-end ds-txt-green"><?= $fmt($weekRevenue) ?></td>
                                <td class="text-end ds-txt-red"><?= $fmt($weekExpenses) ?></td>
                                <td class="text-end <?= $weekNet >= 0 ? 'ds-txt-green' : 'ds-txt-red' ?>"><?= $fmt($weekNet) ?></td>
                                <td colspan="3"></td>
                            </tr>
                        <?php endif; ?>

                        <?php
                        $currentWeek = $week;
                        $weekFirst = $info['date'];
                        if (empty($weekLast) || strtotime($info['date']) > strtotime($weekLast)) $weekLast = $info['date'];
                        $weekCount += $info['count'];
                        $weekRevenue += $info['revenue'];
                        $weekExpenses += $info['expenses'];
                        $weekNet += $info['net'];

                        $isWeekend = in_array($info['weekday'], ['Сб', 'Вс'], true);
                        $isMaxNet = $info['date'] === $summary['maxNetDay'] && $info['net'] > 0;
                        $isMaxExp = $info['date'] === $summary['maxExpenseDay'] && $summary['maxDayExpense'] > 0;
                        $netClass = $info['net'] > 0 ? 'ds-txt-green fw-semibold' : ($info['net'] < 0 ? 'ds-txt-red fw-semibold' : 'text-secondary');
                        $rowClass = trim(($isWeekend ? ' ds-row-weekend' : '') . ($isMaxNet ? ' ds-row-max-net' : '') . ($isMaxExp ? ' ds-row-max-exp' : ''));
                        $percent = $info['expenses'] > 0 ? (int)round($info['expenses'] / $maxExpense * 100) : 0;
                        $barColor = $percent >= 80 ? '#c0392b' : ($percent >= 45 ? '#e67e22' : '#2e8b57');
                        $avgOp = $info['expenses'] > 0 && $info['count'] > 0 ? $info['expenses'] / $info['count'] : 0;
                        $top = !empty($info['rows']) ? array_keys($info['rows'])[0] : null;
                        ?>
                        <tr class="ds-day-row<?= $rowClass ?>">
                            <td class="ds-th-day">
                                <span class="ds-day-num"><?= $info['day'] ?></span>
                                <span class="ds-day-week <?= $isWeekend ? 'ds-day-weekend' : '' ?>"><?= $info['weekday'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($info['count'] > 0): ?>
                                    <span class="ds-badge-count"><?= $info['count'] ?></span>
                                <?php else: ?>
                                    <span class="text-secondary">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end ds-txt-green"><?= $info['revenue'] > 0 ? $fmt($info['revenue']) : '<span class="text-secondary">—</span>' ?></td>
                            <td class="text-end ds-txt-red"><?= $info['expenses'] > 0 ? $fmt($info['expenses']) : '<span class="text-secondary">—</span>' ?></td>
                            <td class="text-end <?= $netClass ?>"><?= $info['count'] > 0 ? $fmt($info['net']) : '<span class="text-secondary">—</span>' ?></td>
                            <td class="text-end text-secondary"><?= $avgOp > 0 ? $fmt($avgOp) : '—' ?></td>
                            <td>
                                <?php if ($top !== null): ?>
                                    <span class="ds-top-cat" title="<?= Html::encode($top) ?>">
                                        <?= Html::encode($top) ?>
                                        <small>(<?= $fmt($info['rows'][$top]) ?>)</small>
                                    </span>
                                <?php else: ?>
                                    <span class="text-secondary">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="ds-progress" title="<?= $percent ?>% от макс. дневного расхода">
                                    <div class="ds-progress-bar" style="width: <?= $percent ?>%; background: <?= $barColor ?>;"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($currentWeek !== null): ?>
                        <tr class="ds-week-total">
                            <td colspan="2">
                                <i class="bi bi-calendar-week me-1"></i>Неделя <?= $currentWeek ?>
                                <span class="ds-week-range"><?= date('d.m', strtotime($weekFirst)) ?> – <?= date('d.m', strtotime($weekLast)) ?></span>
                            </td>
                            <td class="text-end ds-txt-green"><?= $fmt($weekRevenue) ?></td>
                            <td class="text-end ds-txt-red"><?= $fmt($weekExpenses) ?></td>
                            <td class="text-end <?= $weekNet >= 0 ? 'ds-txt-green' : 'ds-txt-red' ?>"><?= $fmt($weekNet) ?></td>
                            <td colspan="3"></td>
                        </tr>
                    <?php endif; ?>

                    <tr class="ds-table-total">
                        <td colspan="2"><i class="bi bi-flag-fill me-1"></i>Итого</td>
                        <td class="text-end ds-txt-green fw-bold"><?= $fmt($summary['totalRevenue']) ?></td>
                        <td class="text-end ds-txt-red fw-bold"><?= $fmt($summary['totalExpenses']) ?></td>
                        <td class="text-end <?= $totalNet >= 0 ? 'ds-txt-green' : 'ds-txt-red' ?> fw-bold"><?= $fmt($totalNet) ?></td>
                        <td colspan="3"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($summary['activeDays'] > 0): ?>
<?php
$labels = range(1, $statistic['daysInMonth']);
$expensesData = [];
$revenueData = [];
for ($d = 1; $d <= $statistic['daysInMonth']; $d++) {
    $key = sprintf('%04d-%02d-%02d', $year, $month, $d);
    $expensesData[] = isset($days[$key]) ? round($days[$key]['expenses'], 2) : 0;
    $revenueData[] = isset($days[$key]) ? round($days[$key]['revenue'], 2) : 0;
}
$jsExpenses = json_encode($expensesData);
$jsRevenue = json_encode($revenueData);
$jsLabels = json_encode($labels);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [
    'position' => $this::POS_HEAD,
]);

$this->registerCss(<<<'CSS'
.ds-page { color: #2c3e50; }
.ds-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 16px;
  box-shadow: 0 6px 24px rgba(30, 60, 114, 0.08);
  overflow: hidden;
}
.ds-card-head {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
  padding: 16px 20px; border-bottom: 1px solid #eef1f6;
}
.ds-card-title { margin: 0; font-weight: 700; color: #1f2d3d; }
.ds-card-badge {
  background: #eef2ff; color: #3b5bdb; font-size: 12px; font-weight: 600;
  padding: 4px 12px; border-radius: 999px;
}
.ds-card-body { padding: 20px; }

/* Фильтр */
.ds-filter-inner {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
  padding: 14px 20px;
}
.ds-filter-form { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 12px; }
.ds-filter-label { display: flex; flex-direction: column; gap: 4px; font-size: 12px; color: #6b7a90; font-weight: 600; }
.ds-period-title { font-size: 18px; font-weight: 800; color: #1f2d3d; }
.ds-btn-primary {
  background: linear-gradient(135deg, #4e73df, #6a11cb);
  color: #fff; font-weight: 600; border: none; border-radius: 10px; padding: 6px 18px;
}
.ds-btn-primary:hover { color: #fff; transform: translateY(-1px); }

/* Сводные карточки */
.ds-summary {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 16px;
}
.ds-summary-card {
  display: flex; align-items: center; gap: 14px;
  border-radius: 16px; padding: 16px 18px; color: #fff;
  box-shadow: 0 8px 22px rgba(30, 60, 114, 0.14);
  transition: transform .2s ease, box-shadow .2s ease;
}
.ds-summary-card:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(30, 60, 114, 0.18); }
.ds-sum-icon {
  width: 46px; height: 46px; border-radius: 14px; background: rgba(255,255,255,.22);
  display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
}
.ds-sum-label { font-size: 12px; opacity: .9; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.ds-sum-value { font-size: 20px; font-weight: 800; line-height: 1.2; }
.ds-sum-value small { font-size: 11px; font-weight: 500; opacity: .85; }
.ds-sum-card-green { background: linear-gradient(135deg, #2e8b57, #27ae60); }
.ds-sum-card-red { background: linear-gradient(135deg, #c0392b, #e74c3c); }
.ds-sum-card-blue { background: linear-gradient(135deg, #2453d0, #4e73df); }
.ds-sum-card-dark { background: linear-gradient(135deg, #34495e, #2c3e50); }
.ds-sum-card-gold { background: linear-gradient(135deg, #b8860b, #f6c23e); color: #2c3e50; }
.ds-sum-card-violet { background: linear-gradient(135deg, #6a11cb, #2575fc); }

/* Легенда таблицы */
.ds-legend { display: flex; flex-wrap: wrap; gap: 14px; font-size: 12px; color: #6b7a90; }
.ds-legend-item i { margin-right: 4px; }

/* Таблица */
.ds-table { font-size: 13.5px; }
.ds-table thead th {
  background: #f6f8fc; color: #5a6b83; font-weight: 700; font-size: 12px;
  text-transform: uppercase; letter-spacing: .4px; padding: 10px 12px; border-bottom: 2px solid #e5e9f0;
  position: sticky; top: 0; z-index: 1;
}
.ds-table td { padding: 9px 12px; border-bottom: 1px solid #eef1f6; }
.ds-th-day { min-width: 84px; }
.ds-day-row { transition: background .15s ease; }
.ds-day-row:hover { background: #f4f7ff; }
.ds-row-weekend { background: #fdf3f0; }
.ds-row-weekend:hover { background: #fbece6; }
.ds-row-max-net { background: #eefaf1; }
.ds-row-max-net:hover { background: #e4f6ea; }
.ds-row-max-exp { background: #fdf0f0; }
.ds-row-max-exp:hover { background: #fae4e4; }

.ds-day-num { font-weight: 800; font-size: 16px; color: #1f2d3d; }
.ds-day-week {
  display: inline-block; margin-left: 6px; font-size: 11px; font-weight: 700; color: #3b5bdb;
  background: #eef2ff; border-radius: 999px; padding: 2px 8px; vertical-align: 2px;
}
.ds-day-weekend { color: #c0392b; background: #fdeaea; }
.ds-badge-count {
  background: #1f2d3d; color: #fff; font-weight: 700; font-size: 12px;
  border-radius: 999px; padding: 3px 10px;
}
.ds-txt-green { color: #2e8b57 !important; }
.ds-txt-red { color: #c0392b !important; }
.ds-top-cat {
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px;
  display: inline-block; color: #3b5bdb; font-weight: 600;
}
.ds-top-cat small { color: #8a97a8; font-weight: 500; }

/* Прогресс расходов */
.ds-progress {
  height: 10px; background: #eef1f6; border-radius: 999px; overflow: hidden;
}
.ds-progress-bar { height: 100%; border-radius: 999px; transition: width .3s ease; }

/* Итог недели */
.ds-week-total {
  background: #f2f6ff !important;
}
.ds-week-total td { font-weight: 700; color: #3b5bdb; border-top: 2px solid #dfe7fb; }
.ds-week-range { font-size: 11px; color: #8a97a8; font-weight: 500; margin-left: 6px; }
.ds-table-total {
  background: linear-gradient(135deg, #4e73df, #6a11cb) !important; color: #fff !important;
}
.ds-table-total td { font-weight: 800; color: #fff !important; border-bottom: none; }

@media (max-width: 768px) {
  .ds-period-title { width: 100%; }
}
CSS
);

$this->registerJs(<<<JS
(function () {
    var ctx = document.getElementById('dsChart');
    if (!ctx) return;
    var labels = $jsLabels;
    var expenses = $jsExpenses;
    var revenue = $jsRevenue;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Расходы',
                    data: expenses,
                    backgroundColor: 'rgba(192, 57, 43, .75)',
                    borderColor: '#c0392b',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Доходы',
                    data: revenue,
                    backgroundColor: 'rgba(46, 139, 87, .75)',
                    borderColor: '#2e8b57',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                x: {
                    stacked: false,
                    grid: { display: false },
                    ticks: { font: { size: 11 }, autoSkip: false, maxRotation: 0 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#eef1f6' },
                    ticks: {
                        font: { size: 11 },
                        callback: function (v) { return v.toLocaleString('ru-RU'); }
                    }
                }
            },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 14 } },
                tooltip: {
                    callbacks: {
                        label: function (c) {
                            return c.dataset.label + ': ' + c.parsed.y.toLocaleString('ru-RU', {maximumFractionDigits: 0}) + ' руб.';
                        }
                    }
                }
            }
        }
    });
})();
JS
);
endif;