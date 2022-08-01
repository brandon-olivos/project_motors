<?php

namespace app\modules\proformas\bundles;

use yii\web\AssetBundle;

class ProformasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/proformas/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/mensaje.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
