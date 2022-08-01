<?php

namespace app\modules\proformas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;

use app\models\Articulos;
use app\models\Proforma;
use app\models\DetallesProforma;
use yii\filters\AccessControl;

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
                            'listaproformas',
                            'get-modal-whatsapp',
                            'crear',
                            'create',
                            'imprimir-proforma'
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


    public function actionCrear(){

        $articulos =  Articulos::find()->where(["estado_articulo" => 1])->all();

        return $this->render('crear', [
           
            "articulos" => $articulos,
        ]);
    }

    public function actionGetModalWhatsapp() {

        $plantilla = Yii::$app->controller->renderPartial("whatsapp", [
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }


    public function actionListaproformas(){

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length =  $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarProformas(:row,:length,:buscar)');
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

                "id_proforma" => $row['id_proforma'],
                "fecha" => $row['fecha'],
                "numero_proforma" => $row['numero_proforma'],
                "total" => $row['total'],
               
                "accion" => '<a title="PDF" class="btn btn-sm btn-light-danger font-weight-bold mr-2" target="_blank" href="proformas/default/imprimir-proforma/' . $row["id_proforma"] . '"><i class="fas fa-file-alt"></i></a>'

                        // <button title="Whatsapp" class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="abrirWhatsapp('. $row["id_proforma"] .',\''. $row["numero_proforma"] .'\');"><i class="fab fa-whatsapp"></i></button>',
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


    public function actionCreate(){
        
        //Obtener numero filas
        $command = Yii::$app->db->createCommand('select count(id_proforma) as cantidad from proforma');

        $result = $command->queryAll();
        $cantidad = 0;

        foreach ($result as $k => $row) {
            $cantidad= $row['cantidad'];
        }

        $cantidad++;
        $num = strlen($cantidad);
        $espacio = 12 - $num;
        $numproforma = substr("P001-00000000", 0, $espacio);
        $numproforma = $numproforma.($cantidad);

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                //VENTA ARTICULOS
                $proforma = new Proforma();

                $proforma->numero_proforma = $numproforma;
                $proforma->id_usuario_reg = Yii::$app->user->getId();
                $proforma->fecha_reg = Utils::getFechaActual();
                $proforma->total = $post["total_articulos"];
                $proforma->ipmaq_reg = Utils::obtenerIP();

                if (!$proforma->save()) {
                    Utils::show($proforma->getErrors(), true);
                    throw new HttpException("No se puede guardar datos proforma de productos");
                }

                ////////////////////////////////////////////////////////////////

                for ($i=0; $i < count($post["array_articulos"]); $i++) {

                    $ar_articulo = $post["array_articulos"][$i];
                    $ar_precio_uni = $post["array_precio_uni"][$i];
                    $ar_cantidad = $post["array_cantidad"][$i];
                    $ar_subtotal = $post["array_subtotal"][$i];
                    $ar_stock = $post["array_stock"][$i];


                    //DETALLE PROFORMA
                    $detalle = new DetallesProforma();

                    $detalle->id_proforma = $proforma->id_proforma;
                    $detalle->id_articulo = $ar_articulo;    
                    $detalle->precio_unitario = $ar_precio_uni;
                    $detalle->cantidad = $ar_cantidad;
                    $detalle->subtotal = $ar_subtotal;

                    if (!$detalle->save()) {
                        Utils::show($detalle->getErrors(), true);
                        throw new HttpException("No se puede guardar datos detalle");
                    }
                }

                $transaction->commit();

            } catch (Exception $ex) {
                
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $proforma->id_proforma;
        } 

        else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }




    public function actionImprimirProforma($id) {

        $command = Yii::$app->db->createCommand('

            SELECT p.id_proforma as id_proforma, p.numero_proforma as numero_proforma, p.fecha_reg as fecha, p.total as total, u.usuario as usuario FROM proforma p
                INNER JOIN usuarios u on p.id_usuario_reg = u.id_usuario where p.id_proforma = '.$id.';
                ');

        $result = $command->queryOne();

        $command = Yii::$app->db->createCommand('

            SELECT d.id_detalle_proforma as id_detalle_proforma, d.id_proforma as id_proforma, a.nombre_articulo as articulo, d.precio_unitario as precio_unitario, d.cantidad as cantidad, d.subtotal as subtotal ,(select count(*) from detalles_proforma where id_proforma = '.$id.') as total

                FROM detalles_proforma d 
                INNER JOIN motos_articulos a on d.id_articulo = a.id_articulo 
                INNER JOIN proforma v on d.id_proforma = v.id_proforma
                where d.id_proforma  = '.$id.';
                ');

        $resultdetal = $command->queryAll();

        $idUser = Yii::$app->user->getId();
        $actual = date("Y-m-d H:i:s");

        $user = \app\models\Usuarios::findOne($idUser);

        $pdf = new \FPDF();

        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/proformas/assets/images/logopegaso.png'), 55, 1, 90);
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->Ln(40);
        $textypos = 55;
        $x=0;
        $pdf->Cell(5);
        $pdf->setY($x=$x+9);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, $textypos, "PEGASO SERVICE EXPRESS S.A.C.");

        $pdf->setY($x=$x+40);
        $pdf->setX(76);
        $pdf->SetFont('ARIAL', '', 13);
        $pdf->Cell(50, 0, "PROFORMAS PEGASO");

        $pdf->setY($x=$x+14);
        $pdf->setX(15);
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Cell(5, 0, $result["numero_proforma"]);

        $pdf->setY($x);
        $pdf->setX(140);
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Cell(5, 0, "Lima, ".$result["fecha"]);


        $pdf->setY($x=$x+10);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Cell(50, 0, "Sr(s)          USUARIO");

        $pdf->setY($x=$x+5);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Cell(50, 0, "Direccion Lima");

        $pdf->setY($x=$x+8);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Cell(50, 0, "ESTIMADO CLIENTE :");

        $pdf->setY($x=$x+5);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 9);
        $pdf->Cell(5, 0, "POR MEDIO DE ESTE PRESENTE LE HACEMOS LLEGAR NUESTRO CORDIAL SALUDO Y A LA VEZ LA PRESENTE");

        $pdf->setY($x=$x+5);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 9);
        $pdf->Cell(5, 0, "COTIZACION DE VUESTRA SOLICITUD:");
    
        $pdf->setY($x=$x+4);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "---------------------------------------------------------------------------------------------------------------------------------------------");

        ////////////////////////////////////////////////////////////////////////////
        // ubicacion cabezera 

        $h_dinamic = 105;

        $pdf->setY($h_dinamic);
        $pdf->setX(10);

        $header = array('CANT', 'DESCRIPCION','P.U.', 'SUBTOTAL');
        $w = array(30, 90,32,25);

        for ($i = 0; $i < count($header); $i++) {

            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, '');
        }

        $pdf->Ln();

        for ($i=0; $i < $resultdetal[0]["total"]; $i++) { 
            
            $h_dinamic = $h_dinamic + 10;

            $pdf->setY($h_dinamic);
            $pdf->setX(10);
            $pdf->SetFont('ARIAL', '', 10);
            $pdf->MultiCell($w[0], 4, "" . $resultdetal[$i]["cantidad"], '', 'J', 0);

            $pdf->setY($h_dinamic);
            $pdf->setX(40);
            $pdf->MultiCell($w[1], 4, "" . utf8_decode($resultdetal[$i]["articulo"]), '', 'J', 0);

            $pdf->setY($h_dinamic);
            $pdf->setX(130);
            $pdf->MultiCell($w[2], 4, "S/ " . $resultdetal[$i]["precio_unitario"], '', 'J', 0);

            $pdf->setY($h_dinamic);
            $pdf->setX(162);
            $pdf->MultiCell($w[3], 4, "S/ " . $resultdetal[$i]["subtotal"], '', 'J', 0);
        }


        $pdf->setY($h_dinamic+7);
        $pdf->setX(10);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->MultiCell(0, 4, "---------------------------------------------------------------------------------------------------------------------------------------------", '', 'J', 0);

        $igvdet = round($result["total"]*0.18,2);

        

        $pdf->setY($h_dinamic+17);
        $pdf->setX(129);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY($h_dinamic+17);
        $pdf->setX(162);
        $pdf->MultiCell(0, 4, "S/ " . $result["total"], '', 'J', 0);

       
        $pdf->setY($h_dinamic+25);
        $pdf->setX(10);
        $pdf->MultiCell(0, 4, "---------------------------------------------------------------------------------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(270);
        $pdf->setX(10);
        $pdf->MultiCell(0, 4, "Realizado por : " . $result['usuario'], '', 'J', 0);
        $pdf->setY(275);
        $pdf->setX(10);
        $pdf->MultiCell(0, 4, "Registrado: " . $result['fecha'], '', 'J', 0);

        $pdf->setY(265);
        $pdf->setX(10);
        $pdf->MultiCell(0, 4, "---------------------------------------------------------------------------------------------------------------------------------------------", '', 'J', 0);

        $pdf->Output();
    }

}
