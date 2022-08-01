<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalles_proforma".
 *
 * @property int $id_detalle_proforma
 * @property int $id_proforma
 * @property int $id_articulo
 * @property float $precio_unitario
 * @property int $cantidad
 * @property float $subtotal
 */
class DetallesProforma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalles_proforma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_proforma', 'id_articulo', 'precio_unitario', 'cantidad', 'subtotal'], 'required'],
            [['id_proforma', 'id_articulo', 'cantidad'], 'integer'],
            [['precio_unitario', 'subtotal'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_proforma' => 'Id Detalle Proforma',
            'id_proforma' => 'Id Proforma',
            'id_articulo' => 'Id Articulo',
            'precio_unitario' => 'Precio Unitario',
            'cantidad' => 'Cantidad',
            'subtotal' => 'Subtotal',
        ];
    }
}
