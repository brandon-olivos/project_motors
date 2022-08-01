<?php

namespace app\modules\marcas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Articulosmarca;
use yii\filters\AccessControl;

/**
 * Default controller for the `articulosmarca` module
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
                            'lista'
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


    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listarArticulosMarca(:row,:length,:buscar)');
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
                "id_marca" => $row['id_marca'],
                "nombre_marca" => $row['nombre_marca'],

                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_marca"] . ')"><i class="flaticon-edit"></i></button',
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

        $plantilla = Yii::$app->controller->renderPartial("crear", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $articulosmarca = new Articulosmarca();
                $articulosmarca->nombre_marca = $post['nombre_marca'];

                if (!$articulosmarca->save()) {
                    Utils::show($articulosmarca->getErrors(), true);
                    throw new HttpException("No se puede guardar datos de marca");
                }
                

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($articulosmarca->id_marca);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articulosmarca->id_marca;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Articulosmarca::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "articulosmarca" => $data
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
                $articulosmarca = Articulosmarca::findOne($post['id_marca']);
                $articulosmarca->nombre_marca = $post['nombre_marca'];

                if (!$articulosmarca->update()) {
                    Utils::show($articulosmarca->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Marca");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($articulosmarca->id_marca);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articulosmarca->id_marca;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }
}
