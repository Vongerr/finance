<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLteAssets
 * @package app\assets
 */
class AdminLteAssets extends  AssetBundle {

    public $sourcePath = '@app/assets/adminlte';

    public $css = [
        'css/AdminLTE.css',
        'css/skins/_all-skins.min.css',
        'css/style.css'
    ];

    public $js = [
        'js/app.js',
        'js/skins.js',
    ];

    public $depends = [
        'app\assets\FontAwesomeAssets',
        'app\assets\AppAsset',
        'app\assets\IonAssets',
        'app\assets\FastClickAssets',
        'app\assets\SlimScrollAssets',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}