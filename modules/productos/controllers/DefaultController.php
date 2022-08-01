<?php

namespace app\modules\productos\controllers;


use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Articulos;
use app\models\Articuloscategoria;
use app\models\Articulosmarca;
use yii\filters\AccessControl;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


/**
 * Default controller for the `articulos` module
 */
class DefaultController extends Controller
{
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'get-modal',
                            'create',
                            'get-modal-edit',
                            'update',
                            'delete',
                            'lista',
                            'exportar',
                            'buscar-marcas',
                            'buscar-categorias',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionIndex() {

        return $this->render('index');   
    }


    public function actionBuscarMarcas(){

        $command = Yii::$app->db->createCommand('SELECT id_marca, nombre_marca from articulos_marca order by id_marca desc');

        $result = $command->queryAll();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionBuscarCategorias(){

        $command = Yii::$app->db->createCommand('SELECT id_categoria, nombre_categoria from articulos_categoria order by id_categoria desc');

        $result = $command->queryAll();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result; 
    }

    public function actionExportar()
    {

        $buscar = $_POST['buscar'];

        $command = Yii::$app->db->createCommand('call listarArticulos_Excel(:buscar,:estado)');
        $command->bindValue(':buscar', $buscar);
        $command->bindValue(':estado', 1);

        $data = $command->queryAll();

        $filename = "Reporte-Articulos.xlsx";
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
        $sheet->setCellValue('C2', 'DETALLE DE PRODUCTOS');

        //Estilo en celda
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 15], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/deivismotors/modules/ventas/assets/images/logo.jpeg');
        $drawing->setCoordinates('A1');

        //Cabecera
        $sheet->getStyle('A6:L6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');

        //style letra de celdas
        $sheet->getStyle('A6:L6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);
        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'ID');
        $sheet->setCellValue('B6', 'Nombre');
        $sheet->setCellValue('C6', 'Código de Barras');
        $sheet->setCellValue('D6', 'Número de Serie');
        $sheet->setCellValue('E6', 'Descripción');
        $sheet->setCellValue('F6', 'Categoría');
        $sheet->setCellValue('G6', 'Marca');
        $sheet->setCellValue('H6', 'Cantidad Contable');
        $sheet->setCellValue('I6', 'Precio Bruto');
        $sheet->setCellValue('J6', 'IGV');
        $sheet->setCellValue('K6', 'Precio de Venta');
        $sheet->setCellValue('L6', 'Fecha de Registro');

        $i = 7;

        foreach ($data as $k => $v) {
            //$fecha = ($v['fecha_reg'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha_reg']));

            $sheet->setCellValue('A' . $i, $v['id_articulo']);
            $sheet->setCellValue('B' . $i, $v['nombre_articulo']);
            $sheet->setCellValue('C' . $i, $v['codigo_barras_articulo']);
            $sheet->setCellValue('D' . $i, $v['numero_serie_articulo']);
            $sheet->setCellValue('E' . $i, $v['desc_articulo']);
            $sheet->setCellValue('F' . $i, $v['nombre_categoria']);
            $sheet->setCellValue('G' . $i, $v['nombre_marca']);    
            $sheet->setCellValue('H' . $i, $v['cantidad_articulo']);
            $sheet->setCellValue('I' . $i, $v['precio_bruto_articulo']);
            $sheet->setCellValue('J' . $i, $v['igv_articulo']);
            $sheet->setCellValue('K' . $i, $v['precio_venta_articulo']);
            $sheet->setCellValue('L' . $i, $v['fecha_reg']);

            $i++;
        }

        
        $i--;
        //bordes
        $sheet->getStyle('A6' . ':L' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $drawing->setWorksheet($sheet);

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

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarArticulos(:row,:length,:buscar,:estado)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':estado', 1);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "id_articulo" => $row['id_articulo'],
                "nombre_articulo" => $row['nombre_articulo'],
                "desc_articulo" => $row['desc_articulo'],
                "nombre_marca" => $row['nombre_marca'],
                "nombre_categoria" => $row['nombre_categoria'],
                "cantidad_articulo" => $row['cantidad_articulo'],
                "precio_venta_articulo" => '<div align="right">S/. '.$row['precio_venta_articulo'].'</div>',
                "fecha_articulo" => $row['fecha_reg'],

                "accion" => '<button title="Editar" class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_articulo"] . ',1)"><i class="flaticon-edit"></i></button>
                
                <button title="Inactivar" class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar('.$row["id_articulo"].',0)"><i class="flaticon-delete"></i></button>',
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



    public function actionGetModal() {

        $articuloscategoria =  Articuloscategoria::find()->all();
        $articulosmarca =  Articulosmarca::find()->all();

        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "articuloscategoria"=>$articuloscategoria, "articulosmarca"=>$articulosmarca 
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $articulos = new Articulos();
                $articulos->nombre_articulo = $post['nombre_articulo'];
                $articulos->desc_articulo = $post['desc_articulo'];
                $articulos->id_categoria = $post['id_categoria'];
                $articulos->id_marca = $post['id_marca'];
                $articulos->cantidad_articulo = 0;
                $articulos->valor_articulo = $post['valor_articulo'];
                $articulos->codigo_barras_articulo = $post['codigo_barras_articulo'];
                $articulos->numero_serie_articulo = $post['numero_serie_articulo'];
                $articulos->estado_articulo = $post['estado_articulo'];

                $articulos->tipo_precio_articulo = $post['tipo_precio_articulo'];
                $articulos->porcentaje_aumento_articulo = $post['porcentaje_aumento_articulo'];
                $articulos->igv_articulo = $post['igv_articulo'];
                $articulos->precio_bruto_articulo = $post['precio_bruto_articulo'];
                $articulos->precio_venta_articulo = $post['precio_venta_articulo'];

                $articulos->id_usuario_reg = Yii::$app->user->getId();
                $articulos->fecha_reg = Utils::getFechaActual();
                $articulos->ipmaq_reg = Utils::obtenerIP();

                if (!$articulos->save()) {
                    Utils::show($articulos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Articulos");
                }
            
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($articulos->id_articulo);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articulos->id_articulo;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }


    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $articulos = Articulos::findOne($post['id_articulo']);
                $articulos->estado_articulo = $post['estado_articulo'];

                if ($post['estado_articulo'] == 1) {
                    
                    $articulos->id_usuario_act = Yii::$app->user->getId();
                    $articulos->fecha_act = Utils::getFechaActual();
                    $articulos->ipmaq_act = Utils::obtenerIP();

                }else{
                    $articulos->id_usuario_del = Yii::$app->user->getId();
                    $articulos->fecha_del = Utils::getFechaActual();
                    $articulos->ipmaq_del = Utils::obtenerIP();
                }
                

                if (!$articulos->save()) {
                    Utils::show($articulos->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro articulos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($articulos->id_articulos);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articulos->id_articulo;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }



    public function actionGetModalEdit($id) {
        $data = Articulos::findOne($id);
        $articuloscategoria =  Articuloscategoria::find()->all();
        $articulosmarca =  Articulosmarca::find()->all();

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "articulos" => $data, "articuloscategoria" => $articuloscategoria, "articulosmarca"=>$articulosmarca
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $articulos = Articulos::findOne($post['id_articulo']);
                $articulos->nombre_articulo = $post['nombre_articulo'];
                $articulos->desc_articulo = $post['desc_articulo'];
                $articulos->id_marca = $post['id_marca'];
                $articulos->id_categoria = $post['id_categoria'];
                $articulos->cantidad_articulo = 0;
                $articulos->valor_articulo = $post['valor_articulo'];
                $articulos->codigo_barras_articulo = $post['codigo_barras_articulo'];
                $articulos->numero_serie_articulo = $post['numero_serie_articulo'];
                $articulos->estado_articulo = $post['estado_articulo'];

                $articulos->tipo_precio_articulo = $post['tipo_precio_articulo'];
                $articulos->porcentaje_aumento_articulo = $post['porcentaje_aumento_articulo'];
                $articulos->igv_articulo = $post['igv_articulo'];
                $articulos->precio_bruto_articulo = $post['precio_bruto_articulo'];
                $articulos->precio_venta_articulo = $post['precio_venta_articulo'];

                $articulos->id_usuario_act = Yii::$app->user->getId();
                $articulos->fecha_act = Utils::getFechaActual();
                $articulos->ipmaq_act = Utils::obtenerIP();

                if (!$articulos->update()) {
                    Utils::show($articulos->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Articulos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($articulos->id_articulo);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articulos->id_articulo;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }
}
