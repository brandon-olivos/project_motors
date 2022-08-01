<?php

namespace app\modules\categorias\bundles;

use yii\web\AssetBundle;

class ArticuloscategoriaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/categorias/assets';
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
