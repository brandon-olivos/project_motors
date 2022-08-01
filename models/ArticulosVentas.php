<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articulos_ventas".
 *
 * @property int $id_articulo_venta
 * @property string $numero_venta
 * @property int $id_usuario_reg
 * @property int $id_tipo_comprobante
 * @property int $id_forma_pago
 * @property string $fecha_reg
 * @property float $total
 * @property string $nota
 * @property int $id_cliente
 * @property int $tipo_documento_cliente
 * @property string $numero_documento_cliente
 * @property string $nombre_razon_cliente
 * @property string $ipmaq_reg
 */
class ArticulosVentas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulos_ventas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario_reg', 'id_tipo_comprobante', 'id_forma_pago', 'fecha_reg', 'total', 'id_cliente', 'tipo_documento_cliente', 'numero_documento_cliente', 'nombre_razon_cliente', 'ipmaq_reg'], 'required'],
            [['id_usuario_reg', 'id_tipo_comprobante', 'id_forma_pago', 'id_cliente', 'tipo_documento_cliente'], 'integer'],
            [['fecha_reg'], 'safe'],
            [['total'], 'number'],
            [['numero_venta', 'ipmaq_reg'], 'string', 'max' => 20],
            [['nota'], 'string', 'max' => 500],
            [['numero_documento_cliente'], 'string', 'max' => 50],
            [['nombre_razon_cliente'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_articulo_venta' => 'Id Articulo Venta',
            'numero_venta' => 'Numero Venta',
            'id_usuario_reg' => 'Id Usuario Reg',
            'id_tipo_comprobante' => 'Id Tipo Comprobante',
            'id_forma_pago' => 'Id Forma Pago',
            'fecha_reg' => 'Fecha Reg',
            'total' => 'Total',
            'nota' => 'Nota',
            'id_cliente' => 'Id Cliente',
            'tipo_documento_cliente' => 'Tipo Documento Cliente',
            'numero_documento_cliente' => 'Numero Documento Cliente',
            'nombre_razon_cliente' => 'Nombre Razon Cliente',
            'ipmaq_reg' => 'Ipmaq Reg',
        ];
    }
}
