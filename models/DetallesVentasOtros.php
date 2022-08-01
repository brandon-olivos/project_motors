<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalles_ventas_otros".
 *
 * @property int $id_detalle_venta_otros
 * @property string $numero_venta_prof
 * @property float|null $mano_obra
 * @property float|null $otros
 */
class DetallesVentasOtros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalles_ventas_otros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_venta_prof'], 'required'],
            [['mano_obra', 'otros'], 'number'],
            [['numero_venta_prof'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_venta_otros' => 'Id Detalle Venta Otros',
            'numero_venta_prof' => 'Numero Venta Prof',
            'mano_obra' => 'Mano Obra',
            'otros' => 'Otros',
        ];
    }
}
