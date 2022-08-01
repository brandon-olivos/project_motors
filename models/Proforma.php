<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proforma".
 *
 * @property int $id_proforma
 * @property string $numero_proforma
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property float $total
 * @property string $ipmaq_reg
 */
class Proforma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proforma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_proforma', 'id_usuario_reg', 'fecha_reg', 'total', 'ipmaq_reg'], 'required'],
            [['id_usuario_reg'], 'integer'],
            [['fecha_reg'], 'safe'],
            [['total'], 'number'],
            [['numero_proforma', 'ipmaq_reg'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proforma' => 'Id Proforma',
            'numero_proforma' => 'Numero Proforma',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'total' => 'Total',
            'ipmaq_reg' => 'Ipmaq Reg',
        ];
    }
}
