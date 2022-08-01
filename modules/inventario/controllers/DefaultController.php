<?php

namespace app\modules\inventario\controllers;


use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use yii\filters\AccessControl;
use app\models\Motivos;
use app\models\ArticulosEntradasSalidas;
use app\models\Opciones;
use app\models\Articulos;



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
                            'lista',
                            'get-modal',
                            'cardex',
                            'createntrada',
                            'createsalida',

                            'get-modal-entrada-crear',
                            'get-modal-salida-crear',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionGetModal() {

        $plantilla = Yii::$app->controller->renderPartial("cardex", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreatesalida() {
        
        if (Yii::$app->request->post()) {
        $transaction = Yii::$app->db->beginTransaction();

        $post = Yii::$app->request->post();

        try {

                $salida = new ArticulosEntradasSalidas();
                $salida->id_articulo = $post['id_articulo'];
                $salida->id_operacion = $post['id_operacion'];
                $salida->id_motivo = $post['id_motivo'];
                $salida->nota = $post['nota'];

                $salida->cantidad = $post['cantidad_actual'];
                $salida->inventario_contable = $post['inventario_contable'];
                $salida->cantidad_entrada_salida = $post['cantidad_entrada_salida'];

                $salida->id_usuario_reg = Yii::$app->user->getId();
                $salida->fecha_reg = Utils::getFechaActual();
                $salida->ipmaq_reg = Utils::obtenerIP();

                if (!$salida->save()) {
                    Utils::show($salida->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Articulos");
                }

                $articulos = Articulos::findOne($post['id_articulo']);
                $articulos->cantidad_articulo = $post['inventario_contable'];

                if (!$articulos->update()) {
                    Utils::show($articulos->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos de salida de articulos");
                }
            
                $transaction->commit();

            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($salida->id_articulos_entradas_salidas);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $salida->id_articulos_entradas_salidas;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }

    }

    public function actionCreatentrada() {
        
        if (Yii::$app->request->post()) {
        $transaction = Yii::$app->db->beginTransaction();

        $post = Yii::$app->request->post();

        try {

                $entrada = new ArticulosEntradasSalidas();
                $entrada->id_articulo = $post['id_articulo'];
                $entrada->id_operacion = $post['id_operacion'];
                $entrada->id_motivo = $post['id_motivo'];
                $entrada->nota = $post['nota'];

                $entrada->cantidad = $post['cantidad_actual'];
                $entrada->inventario_contable = $post['inventario_contable'];
                $entrada->cantidad_entrada_salida = $post['cantidad_entrada_salida'];

                $entrada->id_usuario_reg = Yii::$app->user->getId();
                $entrada->fecha_reg = Utils::getFechaActual();
                $entrada->ipmaq_reg = Utils::obtenerIP();

                if (!$entrada->save()) {
                    Utils::show($entrada->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Articulos");
                }

                $articulos = Articulos::findOne($post['id_articulo']);
                $articulos->cantidad_articulo = $post['inventario_contable'];

                if (!$articulos->update()) {
                    Utils::show($articulos->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos de entrada de articulos");
                }
            
                $transaction->commit();

            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($entrada->id_articulos_entradas_salidas);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entrada->id_articulos_entradas_salidas;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEntradaCrear() {

        $motivos =  Motivos::find()->where(["id_operacion_motivo" => 1])->all();
        $plantilla = Yii::$app->controller->renderPartial("entradacrear", [
            "motivos"=> $motivos
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionGetModalSalidaCrear() {

        $motivos =  Motivos::find()->where(["id_operacion_motivo" => 2])->all();
        $plantilla = Yii::$app->controller->renderPartial("salidacrear", [
            "motivos"=> $motivos
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCardex($id) {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarArticulosInventarioCardex(:row,:length,:buscar,:id)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':id', $id);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        $color = "";

        foreach ($result as $k => $row) {

            if ($row['id_articulo'] == $id) {
                
                if ($row['nombre_operacion'] == "Entrada") {
                    
                    $color = "green";

                }else{

                    $color = "red";
                }

                $data[] = [
                    "id_articulo" => $row['id_articulo'],
                    "nombre_articulo" => $row['nombre_articulo'],
                    "id_operacion" => $row['id_operacion'],

                    "nombre_operacion" => '<mark style="font-size:15px; background-color: '.$color.';
                    color: white;">'.$row['nombre_operacion'].'</mark>',

                    "id_motivo" => $row['id_motivo'],
                    "nombre_motivo" => $row['nombre_motivo'],
                    "nota" => $row['nota'],
                    "referencia" => $row['referencia'],
                    "cantidad" => $row['cantidad'],

                    "entradasalida" => '<mark style="font-size:15px; background-color: '.$color.';
                    color: white;">'.$row['entradasalida'].'</mark>',

                    "inventario_contable" => $row['inventario_contable'],
                    "fecha" => $row['fecha'],
                    "total" => $row['total'],
                ];
            }
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


    public function actionIndex() {
        
            
        return $this->render('index');    
    }


    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarInventario(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "id_articulo" => $row['id_articulo'],
                "nombre_articulo" => $row['nombre_articulo'],
                "entradas" => $row['entradas'],
                "salidas" => $row['salidas'],
                "cantidad_articulo" => $row['cantidad_articulo'],

                "accion" => '<button title="Kardex" class="btn btn-sm btn-light-info font-weight-bold mr-2" onclick="funcionCardex(\''. $row["id_articulo"] .'\',\''. $row["nombre_articulo"] .'\');"><i class="fas fa-box"></i></button>

                <button title="Entrada" class="btn btn-sm btn-light-success font-weight-bold" onclick="funcionCrearEntrada(\''. $row["id_articulo"] .'\',\''. $row["nombre_articulo"] .'\',\''. $row["cantidad_articulo"] .'\');"><i class="fas fa-plus"></i></button>

                <button title="Salida" class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionCrearSalida(\''. $row["id_articulo"] .'\',\''. $row["nombre_articulo"] .'\',\''. $row["cantidad_articulo"] .'\');"><i class="fas fa-minus"></i></button>',
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
}
