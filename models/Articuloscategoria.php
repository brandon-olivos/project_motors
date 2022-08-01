<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articulos_categoria".
 *
 * @property int $id_categoria
 * @property string $nombre_categoria
 */
class Articuloscategoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulos_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_categoria'], 'required'],
            [['nombre_categoria'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_categoria' => 'Id Categoria',
            'nombre_categoria' => 'Nombre Categoria',
        ];
    }
}
