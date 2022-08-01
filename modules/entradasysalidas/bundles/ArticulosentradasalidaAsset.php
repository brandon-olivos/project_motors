<?php

namespace app\modules\entradasysalidas\bundles;

use yii\web\AssetBundle;

class ArticulosentradasalidaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/entradasysalidas/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/entradas.js',
        'js/salidas.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
