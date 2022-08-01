<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articulos_marca".
 *
 * @property int $id_marca
 * @property string $nombre_marca
 */
class Articulosmarca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulos_marca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_marca'], 'required'],
            [['nombre_marca'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_marca' => 'Id Marca',
            'nombre_marca' => 'Nombre Marca',
        ];
    }
}
