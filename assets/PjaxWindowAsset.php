<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\widgets\PjaxAsset;

class PjaxWindowAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/pjax-window';

    public $depends = [
        PjaxAsset::class,
        JqueryAsset::class,
    ];

    public $js = [
        'js/script.js',
    ];
}