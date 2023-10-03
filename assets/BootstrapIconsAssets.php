<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapIconsAssets extends AssetBundle
{
    public $sourcePath = '@app/assets/bootstrap-icons';

    public $css = [
        'bootstrap-icons.min.css',
    ];

    public $depends = [
        AdminLteAssets::class,
    ];
}