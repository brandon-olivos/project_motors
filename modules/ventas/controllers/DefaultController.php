<?php

namespace app\modules\ventas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;

use app\models\Articulos;
use app\models\ArticulosVentas;
use app\models\FormaPago;
use app\models\TipoComprobante;
use app\models\TipoDocumentos;
use app\models\DetallesArticulosVentas;
use app\models\ArticulosEntradasSalidas;
use app\models\Entidades;
use app\models\ClientesVentas;
use yii\filters\AccessControl;
use app\models\DetallesVentasOtros;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use yii\helpers\Url;


class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'buscar-documento',
                            'crear',
                            'create',
                            'listaventas',
                            'get-modal-detalle',
                            'detalles',
                            'exportarexcel',
                            'get-modal-cliente',
                            'imprimir-boleta-factura',
                            'total-ventas',
                            'buscarotrosdetalles',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){

        return $this->render('index');
    }

    public function actionBuscarotrosdetalles(){

        $numero_venta = $_POST["numero_venta"];

        $command = Yii::$app->db->createCommand('SELECT numero_venta_prof, mano_obra,otros from detalles_ventas_otros WHERE numero_venta_prof = "'.$numero_venta.'"');

        $result = $command->queryAll();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionExportarexcel(){

        $fecha = $_POST['fecha'];

        $command = Yii::$app->db->createCommand('call listarArticulosVentas_Excel(:buscar)');
        $command->bindValue(':buscar', "");

        $data = $command->queryAll();

        $filename = "Lista-Ventas".$fecha.".xlsx";
        $spreadsheet = new Spreadsheet();
        $styleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ]
        ];

        $styleBold = [
            'font' => [
                'bold' => true,
                //'size' => 13
            ],
        ];

        $sheet = $spreadsheet->getActiveSheet();

        //Unir celdas
        $sheet->mergeCells("C2:E2");

        //Escribe en celda
        $sheet->setCellValue('C2', 'LISTA DE VENTAS');

        //Estilo en celda
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 15], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/deivismotors/modules/ventas/assets/images/logo.jpeg');
        $drawing->setCoordinates('A1');

        //Cabecera
        $sheet->getStyle('A6:L6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');

        //style letra de celdas
        $sheet->getStyle('A6:J6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);
        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'Fecha y Hora');
        $sheet->setCellValue('B6', 'Comprobante');
        $sheet->setCellValue('C6', 'N°Venta');
        $sheet->setCellValue('D6', 'Cliente');
        $sheet->setCellValue('E6', 'Tipo Documento');
        $sheet->setCellValue('F6', 'N°Documento');
        $sheet->setCellValue('G6', 'Forma de pago');
        $sheet->setCellValue('H6', 'Cajero');
        $sheet->setCellValue('I6', 'Total');
        $i = 7;
        $total = 0;
        foreach ($data as $k => $v) {
            //$fecha = ($v['fecha_reg'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha_reg']));

            $sheet->setCellValue('A' . $i, $v['fecha']);
            $sheet->setCellValue('B' . $i, $v['comprobante']);
            $sheet->setCellValue('C' . $i, $v['numero_venta']);
            $sheet->setCellValue('D' . $i, $v['nombre_cliente']);
            $sheet->setCellValue('E' . $i, $v['tipo_doc_cliente']);
            $sheet->setCellValue('F' . $i, $v['numero_doc_cliente']);
            $sheet->setCellValue('G' . $i, $v['forma_pago']);    
            $sheet->setCellValue('H' . $i, $v['usuario']);
            $sheet->setCellValue('I' . $i, $v['total']);

            $total+= $v['total'];
            $i++;
        }

        $i--;
        //bordes
        $sheet->getStyle('A6' . ':I' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $drawing->setWorksheet($sheet);


        //TOTAL
        $i = intVal($i)+2;
        $sheet->setCellValue('H'.$i, 'TOTAL  S/.');
        $sheet->setCellValue('I'.$i, $total);
        ///////


        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/vnd.ms-excel');
        $headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');

        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }
    
    public function actionCrear(){

        $formapago = FormaPago::find()->where(["fecha_del" => null])->all();
        $tipodocumento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $tipocomprobante = TipoComprobante::find()->where(["fecha_del" => null])->all();
        $articulos =  Articulos::find()->where(["estado_articulo" => 1])->all();
        $clientes =  ClientesVentas::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
           
            "tipocomprobante" => $tipocomprobante,
            "tipodocumento" => $tipodocumento,
            "formapago" => $formapago,
            "articulos" => $articulos,
            "clientes" => $clientes,
        ]);
    }


    public function actionTotalVentas(){

        $dia_inicio = $_POST["dia_inicio"];
        $dia_fin = $_POST["dia_fin"];
        $tipo = $_POST["tipo"];
        $subsint = 0;

        if ($tipo == "dia") {
            $subsint = 13;
        }
        else if($tipo == "mes") {
            $subsint = 10;
        }

        $command = Yii::$app->db->createCommand('SELECT fecha_reg, sum(total) as total from articulos_ventas WHERE fecha_reg BETWEEN "'.$dia_inicio.'" AND "'.$dia_fin.'" group by SUBSTRING(fecha_reg, 1, '.$subsint.') order by fecha_reg asc;');

        $result = $command->queryAll();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }


    public function actionBuscarDocumento(){

        $numero_documento = $_POST["numero_documento"];
        $tipo_documento = $_POST["tipo_documento"];

        $tipodc = null;
        if ($tipo_documento == 1) {

            $tipodc = 'dni';
        } else if ($tipo_documento == 2) {
            $tipodc = 'ruc';
        }

        $docservico = Utils::getConsultaDocumento($tipodc, $numero_documento);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $docservico;
    }

    public function actionCreate(){
        
        //Obtener numero filas
        $command = Yii::$app->db->createCommand('select count(id_articulo_venta) as cantidad from articulos_ventas');

        $result = $command->queryAll();
        $cantidad = 0;

        foreach ($result as $k => $row) {
            $cantidad= $row['cantidad'];
        }

        $cantidad++;
        $num = strlen($cantidad);
        $espacio = 12 - $num;
        $numventa = substr("A001-00000000", 0, $espacio);
        $numventa = $numventa.($cantidad);

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                //VENTA ARTICULOS
                $venta = new Articulosventas();

                $venta->numero_venta = $numventa;
                $venta->id_usuario_reg = Yii::$app->user->getId();         
                $venta->id_tipo_comprobante = $post["id_tipo_comprobante"];
                $venta->id_forma_pago = $post["id_forma_pago"];
                $venta->fecha_reg = $post["fecha"];

                $venta->total = $post["total_articulos"];
                $venta->nota = "";
                $venta->id_cliente = $post["id_cliente"];
                $venta->tipo_documento_cliente = $post["tipo_documento"];
                $venta->numero_documento_cliente = $post["numero_documento"];
                $venta->nombre_razon_cliente = $post["nombre_cliente"];
                $venta->ipmaq_reg = Utils::obtenerIP();

                if (!$venta->save()) {
                    Utils::show($venta->getErrors(), true);
                    throw new HttpException("No se puede guardar datos venta de productos");
                }

                ////////////////////////////////////////////////////////////////

                for ($i=0; $i < count($post["array_articulos"]); $i++) {

                    $ar_articulo = $post["array_articulos"][$i];
                    $ar_precio_uni = $post["array_precio_uni"][$i];
                    $ar_cantidad = $post["array_cantidad"][$i];
                    $ar_subtotal = $post["array_subtotal"][$i];
                    $ar_stock = $post["array_stock"][$i];


                    //DETALLE VENTA ARTICULOS
                    $detalle = new DetallesArticulosVentas();

                    $detalle->id_articulo_venta = $venta->id_articulo_venta;
                    $detalle->id_articulo = $ar_articulo;    
                    $detalle->precio_unitario = $ar_precio_uni;
                    $detalle->cantidad = $ar_cantidad;
                    $detalle->subtotal = $ar_subtotal;

                    if (!$detalle->save()) {
                        Utils::show($detalle->getErrors(), true);
                        throw new HttpException("No se puede guardar datos detalle");
                    }


                    //CAMBIAR STOCK DISPONIBLE
                    $articulos = Articulos::findOne($ar_articulo);
                    $articulos->cantidad_articulo = intVal($ar_stock) - intVal($ar_cantidad);

                    if (!$articulos->update()) {
                        Utils::show($articulos->getErrors(), true);
                        throw new HttpException("No se puede actualizar datos de salida de articulos");
                    }


                    //INGRESAR LA SALIDA DEL PRODUCTO
                    $salida = new ArticulosEntradasSalidas();
                    $salida->id_articulo = $ar_articulo;
                    $salida->id_operacion = 2; //   2 = SALIDA ||  1 = ENTRADA
                    $salida->id_motivo = 8; // 8 = VENTA
                    $salida->nota = "";

                    $salida->cantidad = $ar_stock;
                    $salida->inventario_contable = intVal($ar_stock) - intVal($ar_cantidad);
                    $salida->cantidad_entrada_salida = $ar_cantidad;

                    $salida->id_usuario_reg = Yii::$app->user->getId();
                    $salida->fecha_reg = $post["fecha"];
                    $salida->ipmaq_reg = Utils::obtenerIP();

                    if (!$salida->save()) {
                        Utils::show($salida->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Articulos");
                    }


                    //INGRESAR A TABLA DETALLES OTROS
                    $detotros = new DetallesVentasOtros();
                    $detotros->numero_venta_prof = $numventa;
                    $detotros->mano_obra = $post["mano_obra"];
                    $detotros->otros = $post["otros"];

                    if (!$detotros->save()) {
                        Utils::show($detotros->getErrors(), true);
                        throw new HttpException("No se puede guardar datos detalles otros");
                    }
                }

                $transaction->commit();

            } catch (Exception $ex) {
                
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $venta->id_articulo_venta;
        } 

        else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionListaventas(){

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length =  $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarArticulosVentas(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();

        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }


        $data = [];
        foreach ($result as $k => $row) {


            $data[] = [

                "id_articulo_venta" => $row['id_articulo_venta'],
                "fecha" => $row['fecha'],
                "numero_venta" => $row['numero_venta'],
                "nombre_cliente" => $row['nombre_cliente'],
                "tipo_doc_cliente" => $row['tipo_doc_cliente'],
                "numero_doc_cliente" => $row['numero_doc_cliente'],
                "comprobante" => $row['comprobante'],
                "forma_pago" => $row['forma_pago'],
                "nota" => $row['nota'],
                "persona" => $row['persona'],
                "usuario" => $row['usuario'],
                "total" => $row['total'],

                "accion" => '<a title="'. $row["comprobante"] .'" class="btn btn-sm btn-light-danger font-weight-bold mr-2" target="_blank" href="ventas/default/imprimir-boleta-factura/' . $row["id_articulo_venta"] . '"><i class="fas fa-file-alt"></i></a>

                <button title="Detalles" class="btn btn-sm btn-light-info font-weight-bold mr-2" onclick="verDetalleVenta('. $row["id_articulo_venta"] .',\''. $row["numero_venta"] .'\',\''. $row["fecha"] .'\',\''. $row["nombre_cliente"] .'\',\''. $row["tipo_doc_cliente"] .'\',\''. $row["numero_doc_cliente"] .'\',\''. $row["comprobante"] .'\',\''. $row["forma_pago"] .'\',\''. $row["nota"] .'\',\''. $row["persona"] .'\',\''. $row["usuario"] .'\',\''. $row["total"] .'\');"><i class="fas fa-eye"></i></button>',
            ];
        }

        $totalData = isset($result[0]['total_row']) ? $result[0]['total_row'] : 0;
        
        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $totalData
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionGetModalDetalle() {

        $plantilla = Yii::$app->controller->renderPartial("detalle", [
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }


    public function actionDetalles($id) {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length =  $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarArticulosVentasDetalle(:row,:length,:id)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':id', $id);
            $result = $command->queryAll();

        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
         
                $data[] = [

                    "id_detalle_venta" => $row['id_detalle_venta'],
                    "articulo" => $row['articulo'],
                    "precio_unitario" => $row['precio_unitario'],
                    "cantidad" => $row['cantidad'],
                    "subtotal" => $row['subtotal'],
                    "total" => $row['total'],
                ];       
        }

        $totalData = isset($result[0]['total']) ? $result[0]['total'] : 0;
        
        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $totalData
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }



    public function actionImprimirBoletaFactura($id) {

        $command = Yii::$app->db->createCommand('

            SELECT v.id_articulo_venta as id_articulo_venta, v.numero_venta as numero_venta, v.fecha_reg as fecha, v.nombre_razon_cliente as nombre_cliente, d.documento as tipo_doc_cliente, v.numero_documento_cliente as numero_doc_cliente, c.descripcion as comprobante, p.forma_pago as forma_pago, v.total as total, u.usuario as usuario, v.total as total, (select count(*) from articulos_ventas) as total_row 

                FROM articulos_ventas v 

                INNER JOIN tipo_comprobante c on v.id_tipo_comprobante = c.id_tipo_comprobante 
                INNER JOIN forma_pago p on v.id_forma_pago = p.id_forma_pago 
                INNER JOIN tipo_documentos d on v.tipo_documento_cliente = d.id_tipo_documento 
                INNER JOIN usuarios u on v.id_usuario_reg = u.id_usuario
                where v.id_articulo_venta = '.$id.';
                ');

        $result = $command->queryOne();




        $command = Yii::$app->db->createCommand('

            SELECT d.id_detalle_articulo_venta as id_detalle_venta, d.id_articulo_venta as id_articulo_venta, a.nombre_articulo as articulo, d.precio_unitario as precio_unitario, d.cantidad as cantidad, d.subtotal as subtotal ,(select count(*) from detalles_articulos_ventas where id_articulo_venta = '.$id.') as total

            FROM detalles_articulos_ventas d 

            INNER JOIN motos_articulos a on d.id_articulo = a.id_articulo 
            INNER JOIN articulos_ventas v on d.id_articulo_venta = v.id_articulo_venta
            where d.id_articulo_venta = '.$id.';
                ');

        $resultdetal = $command->queryAll();





        $command = Yii::$app->db->createCommand('

            SELECT numero_venta_prof, mano_obra , otros FROM detalles_ventas_otros where numero_venta_prof = "'.$result["numero_venta"].'";');

        $resultotros = $command->queryOne();



        $tipo_doc_ide = '';
        $tipodc = '';

        if ($result["comprobante"] == 'Factura') {
            $tipo_doc_ide = 'RUC: ';
            $tipodc = 'F';
        }else if ($result["comprobante"] == 'Boleta') {
            $tipo_doc_ide = 'DNI: ';
            $tipodc = 'B';
        }

        $idUser = Yii::$app->user->getId();
        $actual = date("Y-m-d H:i:s");

        $user = \app\models\Usuarios::findOne($idUser);

        $pdf = new \FPDF();

        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/ventas/assets/images/logopegaso.png'), 10, 1, 80);
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->Ln(40);
        $textypos = 45;
        $x=0;
        $pdf->Cell(25);
        $pdf->setY($x=$x+9);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 16);
        $pdf->Cell(5, $textypos, "PEGASO SERVICE EXPRESS S.A.C.");

        $pdf->setY($x=$x+30);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "CALLE PABLO DE OLAVIDE 365 URB.COLONIAL");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "RUC: 20524917891");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, $result["comprobante"] . " Electronica");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Numero de Venta: " . $result["numero_venta"]);
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "FECHA: " . $result['fecha']); //----------------------------------------------
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");
        $pdf->setY($x=$x+2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 10);

        $pdf->MultiCell(100, 4, "CLIENTE: " . utf8_decode($result["nombre_cliente"]), '', 'J', 0);
        $pdf->setY($x=$x+9);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "" . $tipo_doc_ide . $result["numero_doc_cliente"]);
        
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");


        ////////////////////////////////////////////////////////////////////////////
        // ubicacion cabezera 

        $h_dinamic = 85;

        $pdf->setY($h_dinamic);
        $pdf->setX(2);

        $header = array('CANT', 'DESCRIP.', 'VALOR');
        $w = array(30, 50, 17);

        for ($i = 0; $i < count($header); $i++) {

            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, '');
        }

        $pdf->Ln();


        for ($i=0; $i < $resultdetal[0]["total"]; $i++) {
            
            $h_dinamic = $h_dinamic + 9;

            $pdf->setY($h_dinamic);
            $pdf->setX(1);
            $pdf->SetFont('ARIAL', '', 9);
            $pdf->MultiCell($w[0], 4, "" . $resultdetal[$i]["cantidad"], '', 'J', 0);

            $pdf->setY($h_dinamic);
            $pdf->setX(15);
            $pdf->MultiCell($w[1], 4, "" . utf8_decode($resultdetal[$i]["articulo"]), '', 'J', 0);

            $pdf->setY($h_dinamic);
            $pdf->setX(80);
            $pdf->MultiCell($w[2], 4, "S/" . $resultdetal[$i]["subtotal"], '', 'J', 0);
        }

        //DETALLES DE OTROS GASTOS
        if (count($resultotros) != 0) {
            
            if ($resultotros['mano_obra'] != "0.00") {
                
                $h_dinamic = $h_dinamic +9;

                $pdf->setY($h_dinamic);
                $pdf->setX(1);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "1", 'J', 0);

                $pdf->setY($h_dinamic);
                $pdf->setX(15);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "Mano de obra", '', 'J', 0);

                $pdf->setY($h_dinamic);
                $pdf->setX(80);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "S/" . $resultotros['mano_obra'], '', 'J', 0);
            }
            if ($resultotros['otros'] != "0.00") {
                
                $h_dinamic = $h_dinamic + 9;

                $pdf->setY($h_dinamic);
                $pdf->setX(1);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "1", '', 'J', 0);

                $pdf->setY($h_dinamic);
                $pdf->setX(15);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "Otros", '', 'J', 0);

                $pdf->setY($h_dinamic);
                $pdf->setX(80);
                $pdf->SetFont('ARIAL', '', 9);
                $pdf->Cell(5, 4, "S/" . $resultotros['otros'], '', 'J', 0);
            }
        }
        
        


        $pdf->setY($h_dinamic+10);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);


        $igvdet = round($result["total"]*0.18,2);


        $pdf->setY($h_dinamic+15);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SUBTOTAL:", '', 'J', 0);
        $pdf->setY($h_dinamic+15);
        $pdf->setX(76);
        $pdf->MultiCell(0, 4, "S/" .round($result["total"]-$igvdet,2) , '', 'J', 0);
        $pdf->setY($h_dinamic+20);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "IGV:", '', 'J', 0);
        $pdf->setY($h_dinamic+20);
        $pdf->setX(76);

        
        $pdf->MultiCell(0, 4, "S/" . $igvdet, '', 'J', 0);

        $pdf->setY($h_dinamic+25);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY($h_dinamic+25);
        $pdf->setX(76);
        $pdf->MultiCell(0, 4, "S/" . $result["total"], '', 'J', 0);

        $pdf->setY($h_dinamic+30);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "", '', 'J', 0);
        $pdf->setY($h_dinamic+30);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 9);
        //$pdf->MultiCell(0, 4, strtoupper(Utils::convertirLetras(1000), '', 'J', 0);

        $pdf->setY($h_dinamic+35);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY($h_dinamic+40);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "Forma de pago: " . utf8_decode($result["forma_pago"]), '', 'J', 0);

        $pdf->setY($h_dinamic+45);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);


        // $pdf->setY(165);
        // $pdf->setX(2);
        // $pdf->MultiCell(0, 4, "AGENCIA: ". $usuarioconsul["nombre_agencia"], '', 'J', 0);
        $pdf->setY($h_dinamic+50);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "USUARIO: " . $result['usuario'], '', 'J', 0);
        $pdf->setY($h_dinamic+55);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "REGISTRO: " . $result['fecha'], '', 'J', 0);

        $pdf->setY($h_dinamic+60);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY($h_dinamic+65);
        $pdf->setX(2);
        $pdf->MultiCell(95, 4, utf8_decode("A la firma de la conformidad de la boleta la empresa se "
                        . "exime de responsabilidad alguna. Respecto de la perdida de encomiendas,"
                        . " la empresa está facultada a pagar hasta 10 veces el valis del flete conforme "
                        . "a la RD-001-2006-MTC/19 Ley de los Servicios Postales. La Empresa no se responsabiliza "
                        . "pos el deterioro, perdida u otra alteracón que pueda sufrir el contenido de encomienda "
                        . "producto del mal embalaje."), '', 'J', 0);


        $pdf->Output();
    }

}
