<?php

namespace app\modules\categorias\controllers;


use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Articuloscategoria;
use yii\filters\AccessControl;


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
            $command = Yii::$app->db->createCommand('call listarArticulosCategoria(:row,:length,:buscar)');
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
                "id_categoria" => $row['id_categoria'],
                "nombre_categoria" => $row['nombre_categoria'],

                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_categoria"] . ')"><i class="flaticon-edit"></i></button',
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

                $articuloscategoria = new Articuloscategoria();
                $articuloscategoria->nombre_categoria = $post['nombre_categoria'];

                if (!$articuloscategoria->save()) {
                    Utils::show($articuloscategoria->getErrors(), true);
                    throw new HttpException("No se puede guardar datos de categoria");
                }
                

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($articuloscategoria->id_categoria);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articuloscategoria->id_categoria;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Articuloscategoria::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "articuloscategoria" => $data
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
                $articuloscategoria = Articuloscategoria::findOne($post['id_categoria']);
                $articuloscategoria->nombre_categoria = $post['nombre_categoria'];

                if (!$articuloscategoria->update()) {
                    Utils::show($articuloscategoria->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos categoria");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($articuloscategoria->id_categoria);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $articuloscategoria->id_categoria;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }
}
