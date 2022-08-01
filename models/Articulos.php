<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motos_articulos".
 *
 * @property int $id_articulo
 * @property string $codigo_barras_articulo
 * @property string $numero_serie_articulo
 * @property string $nombre_articulo
 * @property string $desc_articulo
 * @property int $id_marca
 * @property int $id_categoria
 * @property int $cantidad_articulo
 * @property float $valor_articulo
 * @property int $tipo_precio_articulo
 * @property float $porcentaje_aumento_articulo
 * @property float $igv_articulo
 * @property float $precio_bruto_articulo
 * @property float $precio_venta_articulo
 * @property string $fecha_reg
 * @property int $estado_articulo
 * @property int $id_usuario_reg
 * @property string $ipmaq_reg
 * @property int $id_usuario_act
 * @property string $fecha_act
 * @property string $ipmaq_act
 * @property int $id_usuario_del
 * @property string $fecha_del
 * @property string $ipmaq_del
 */
class Articulos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motos_articulos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_barras_articulo', 'numero_serie_articulo', 'nombre_articulo', 'id_marca', 'id_categoria', 'cantidad_articulo', 'valor_articulo', 'tipo_precio_articulo', 'igv_articulo', 'precio_bruto_articulo', 'precio_venta_articulo','estado_articulo'], 'required'],

            [['codigo_barras_articulo', 'numero_serie_articulo', 'nombre_articulo', 'id_marca', 'id_categoria', 'cantidad_articulo', 'valor_articulo'], 'required'],

            
            [['id_marca', 'id_categoria', 'cantidad_articulo', 'tipo_precio_articulo', 'estado_articulo', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del'], 'integer'],
            [['valor_articulo', 'porcentaje_aumento_articulo', 'igv_articulo', 'precio_bruto_articulo', 'precio_venta_articulo'], 'number'],
            [['fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['codigo_barras_articulo'], 'string', 'max' => 50],
            [['numero_serie_articulo', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['nombre_articulo'], 'string', 'max' => 200],
            [['desc_articulo'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_articulo' => 'Id Articulo',
            'codigo_barras_articulo' => 'Codigo Barras Articulo',
            'numero_serie_articulo' => 'Numero Serie Articulo',
            'nombre_articulo' => 'Nombre Articulo',
            'desc_articulo' => 'Desc Articulo',
            'id_marca' => 'Id Marca',
            'id_categoria' => 'Id Categoria',
            'cantidad_articulo' => 'Cantidad Articulo',
            'valor_articulo' => 'Valor Articulo',
            'tipo_precio_articulo' => 'Tipo Precio Articulo',
            'porcentaje_aumento_articulo' => 'Porcentaje Aumento Articulo',
            'igv_articulo' => 'Igv Articulo',
            'precio_bruto_articulo' => 'Precio Bruto Articulo',
            'precio_venta_articulo' => 'Precio Venta Articulo',
            'fecha_reg' => 'Fecha Reg',
            'estado_articulo' => 'Estado Articulo',
            'id_usuario_reg' => 'Id Usuario Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
        ];
    }
}
