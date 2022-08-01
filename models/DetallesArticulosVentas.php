<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalles_articulos_ventas".
 *
 * @property int $id_detalle_articulo_venta
 * @property int $id_articulo_venta
 * @property int $id_articulo
 * @property float $precio_unitario
 * @property int $cantidad
 * @property float $subtotal
 */
class DetallesArticulosVentas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalles_articulos_ventas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_articulo_venta', 'id_articulo', 'precio_unitario', 'cantidad', 'subtotal'], 'required'],
            [['id_articulo_venta', 'id_articulo', 'cantidad'], 'integer'],
            [['precio_unitario', 'subtotal'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_articulo_venta' => 'Id Detalle Articulo Venta',
            'id_articulo_venta' => 'Id Articulo Venta',
            'id_articulo' => 'Id Articulo',
            'precio_unitario' => 'Precio Unitario',
            'cantidad' => 'Cantidad',
            'subtotal' => 'Subtotal',
        ];
    }
}
