<?php

namespace app\modules\ventas\bundles;

use yii\web\AssetBundle;

class ArticulosVentasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/ventas/assets';
    public $css = [
        'css/main.css',
        'images/logo.png'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/detalle.js',
        'js/exportar.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
