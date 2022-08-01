<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articulos_entradas_salidas".
 *
 * @property int $id_articulos_entradas_salidas
 * @property int $id_articulo
 * @property int $id_operacion
 * @property int $id_motivo
 * @property string $nota
 * @property string $referencia
 * @property int $cantidad
 * @property int $inventario_contable
 * @property int $cantidad_entrada_salida
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 */
class ArticulosEntradasSalidas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulos_entradas_salidas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_articulo', 'id_operacion', 'id_motivo', 'cantidad', 'inventario_contable', 'cantidad_entrada_salida', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['id_articulo', 'id_operacion', 'id_motivo', 'cantidad', 'inventario_contable', 'cantidad_entrada_salida', 'id_usuario_reg'], 'integer'],
            [['fecha_reg'], 'safe'],
            [['nota', 'referencia'], 'string', 'max' => 200],
            [['ipmaq_reg'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_articulos_entradas_salidas' => 'Id Articulos Entradas Salidas',
            'id_articulo' => 'Id Articulo',
            'id_operacion' => 'Id Operacion',
            'id_motivo' => 'Id Motivo',
            'nota' => 'Nota',
            'referencia' => 'Referencia',
            'cantidad' => 'Cantidad',
            'inventario_contable' => 'Inventario Contable',
            'cantidad_entrada_salida' => 'Cantidad Entrada Salida',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
        ];
    }
}
