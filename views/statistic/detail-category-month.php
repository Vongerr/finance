<?php

/* @var $data array */
/* @var $this View */

use app\components\View;
use yii\base\InvalidConfigException;

?>

<div id="app"></div>

<script id="detail-data" type="application/json"><?= json_encode(['financeList' => $data], JSON_UNESCAPED_UNICODE) ?></script>

<?php
try {
    $this->registerJsFile('/js/statistic/detail.js', ['position' => $this::POS_END]);
} catch (InvalidConfigException $e) {

    viewException($e);
}
?>

<style>
    .modern-table {
    width: 100%;
    max-width: 600px;
    border-collapse: separate;
    border-spacing: 0;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    }

    .modern-table thead tr {
    background: linear-gradient(135deg, #6C63FF, #3F3D9E);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .modern-table th, .modern-table td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
    }

    .modern-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
    }

    .modern-table tbody tr:hover {
    background-color: #e9ecef;
    transition: background 0.2s;
    }
</style>
