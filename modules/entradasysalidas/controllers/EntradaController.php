<?php

namespace app\modules\entradasysalidas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\ArticulosEntradasSalidas;
use yii\filters\AccessControl;



class EntradaController extends Controller
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
            $command = Yii::$app->db->createCommand('call listaArticulosEntrada_o_Salida(:row,:length,:buscar,:operacion)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':operacion', 1);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        foreach ($result as $k => $row) {

            $data[] = [
                "id_articulo" => $row['id_articulo'],
                "nombre_articulo" => $row['nombre_articulo'],
                "id_operacion" => $row['id_operacion'],
                "nombre_operacion" => '<mark style="font-size:15px; background-color: green;
                    color: white;">'.$row['nombre_operacion'].'</mark>',
                "id_motivo" => $row['id_motivo'],
                "nombre_motivo" => $row['nombre_motivo'],
                "nota" => $row['nota'],
                "referencia" => $row['referencia'],
                "cantidad" => $row['cantidad'],
                "inventario_contable" => $row['inventario_contable'],
                "fecha" => $row['fecha'],
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


}
