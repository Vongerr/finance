<?php
namespace app\assets;

use yii\web\AssetBundle;

class AdminLteAssets extends AssetBundle {

    public $sourcePath = '@app/assets/adminlte';

    public $css = [
        'css/adminlte.min.css',
        'css/style.css',
    ];

    public $js = [
        'js/adminlte.min.js',
    ];

    public $depends = [
        'yii\bootstrap5\BootstrapPluginAsset',
        'app\assets\FontAwesomeAssets',
    ];
}
