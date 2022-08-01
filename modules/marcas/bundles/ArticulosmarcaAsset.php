<?php

namespace app\modules\marcas\bundles;

use yii\web\AssetBundle;

class ArticulosmarcaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/marcas/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
