<?php

namespace app\modules\inventario\bundles;

use yii\web\AssetBundle;

class ArticulosinventarioAsset extends AssetBundle {

    public $sourcePath = '@app/modules/inventario/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/entrada.js',
        'js/salida.js',
        'js/cardex.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
