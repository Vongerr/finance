<?php

namespace app\assets;

use yii\web\AssetBundle;

class PjaxWindowAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/pjax-window';

    public $js = [
        'js/script.js',
    ];

    public $depends = [
        AdminLteAssets::class,
    ];
}
