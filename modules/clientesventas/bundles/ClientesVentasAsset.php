<?php

namespace app\modules\clientesventas\bundles;

use yii\web\AssetBundle;

class ClientesVentasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/clientesventas/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
