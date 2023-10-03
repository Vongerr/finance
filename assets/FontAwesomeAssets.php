<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAssets
 * @package core\assets
 */
class FontAwesomeAssets extends  AssetBundle {

    public $sourcePath = '@bower/font-awesome';

    public $css = [
        'css/font-awesome.min.css'
    ];

    public $js = [

    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}