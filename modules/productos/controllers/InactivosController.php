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
use yii\filters\AccessControl;


/**
 * Default controller for the `articulos` module
 */
class InactivosController extends Controller
{
 
    public $enableCsrfValidation = false;
 
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
            $command->bindValue(':estado', 0);
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

                "accion" => '<button title="Editar" class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_articulo"] . ',0)"><i class="flaticon-edit"></i></button>

                <button title="Activar" class="btn btn-sm btn-light-info font-weight-bold mr-2" onclick="funcionEliminar('.$row["id_articulo"].',1)"><i class="fa fa-check"></i></button>',
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
