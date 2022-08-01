<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motivos_entrada_salida".
 *
 * @property int $id_motivo
 * @property int $id_operacion_motivo
 * @property string $nombre_motivo
 */
class Motivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motivos_entrada_salida';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_motivo', 'id_operacion_motivo', 'nombre_motivo'], 'required'],
            [['id_motivo', 'id_operacion_motivo'], 'integer'],
            [['nombre_motivo'], 'string', 'max' => 200],
            [['id_motivo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_motivo' => 'Id Motivo',
            'id_operacion_motivo' => 'Id Operacion Motivo',
            'nombre_motivo' => 'Nombre Motivo',
        ];
    }
}
