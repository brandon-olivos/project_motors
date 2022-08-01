<?php

return [
    'gii' => [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ],
    'rbac' => [
        'class' => 'johnitvn\rbacplus\Module',
        'as access' => [
            'class' => 'yii\filters\AccessControl',
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ],
        ],
    ],
    'main' => [
        'class' => 'app\modules\main\Main',
    ],

    //version2
    'clientesventas' => [
        'class' => 'app\modules\clientesventas\ClientesVentas',
    ],

    //version2
    'proformas' => [
        'class' => 'app\modules\proformas\Proformas',
    ],
    //version2

    'ventas' => [
        'class' => 'app\modules\ventas\Articulosventas',
    ],

    'entradasysalidas' => [
        'class' => 'app\modules\entradasysalidas\Articulosentradasalida',
    ],


    'productos' => [
        'class' => 'app\modules\productos\Articulos',
    ],

    'inventario' => [
        'class' => 'app\modules\inventario\Articulosinventario',
    ],

    'categorias' => [
        'class' => 'app\modules\categorias\Articuloscategoria',
    ],

    'marcas' => [
        'class' => 'app\modules\marcas\Articulosmarca',
    ],


    'login' => [
        'class' => 'app\modules\login\LoginModule',
    ],
    'persona' => [
        'class' => 'app\modules\persona\Persona',
    ],
    'seguridad' => [
        'class' => 'app\modules\seguridad\Seguridad',
    ],
    
    'tipodocumento' => [
        'class' => 'app\modules\tipodocumento\TipoDocumento',
    ],

    'dashboard' => [
        'class' => 'app\modules\dashboard\Dashboard',
    ],
    'ubigeos' => [
        'class' => 'app\modules\ubigeos\Ubigeos',
    ],

];
