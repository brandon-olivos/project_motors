<?php

namespace app\modules\seguridad\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
use app\models\Opciones;
use yii\filters\AccessControl;

/**
 * Default controller for the `seguridad` module
 */
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
                            'get-modal',
                            'create',
                            'get-modal-edit',
                            'update',
                            'delete',
                            'lista',
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

    public function actionGetModal(){
        $padres = Opciones::find()->where(["flg_padre" => true, "id_padre" => 0])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearModulo", [
            "padres" => $padres
        ]);
        Utils::jsonEncode(["plantilla" => $plantilla]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $modulo = new Opciones();
                $modulo->nombre_opcion = $post['nombre'];
                $modulo->url = $post['ruta'];
                $modulo->id_padre = $post['padre'];

                if ($post['padre'] == 0) {
                    $modulo->flg_padre = 1;
                }else{
                    $modulo->flg_padre = 0;
                }

                if ($post['ruta'] == "#") {
                    $modulo->flg_padre = 0;
                }

                $modulo->id_usuario_reg = Yii::$app->user->getId();
                $modulo->fecha_reg = Utils::getFechaActual();
                $modulo->ipmaq_reg = Utils::obtenerIP();

                if (!$modulo->save()) {
                    Utils::show($modulo->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Módulo");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($modulo->id_opcion);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id){

        $data = Opciones::findOne($id);
        $padres = Opciones::find()->where(["flg_padre" => true, "id_padre" => 0])->all();

        $plantilla = Yii::$app->controller->renderPartial("editarModulo", [
            "modulo" => $data,
            "padres" => $padres
        ]);
        Utils::jsonEncode(["plantilla" => $plantilla]);
    }

    public function actionUpdate(){

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $modulo = Opciones::findOne($post['id_modulo']);
                $modulo->nombre_opcion = $post['nombre'];
                $modulo->url = $post['ruta'];
                $modulo->id_padre = $post['padre'];

                if ($post['padre'] == 0) {
                    $modulo->flg_padre = 1;
                }else{
                    $modulo->flg_padre = 0;
                }

                if ($post['ruta'] != "#") {
                    $modulo->flg_padre = 0;
                }

                $modulo->id_usuario_act = Yii::$app->user->getId();
                $modulo->fecha_act = Utils::getFechaActual();
                $modulo->ipmaq_act = Utils::obtenerIP();

                if (!$modulo->update()) {
                    Utils::show($modulo->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Módulo");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($modulo->id_opcion);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                $modulo = Opciones::findOne($post['id_modulo']);
                $modulo->id_usuario_del = Yii::$app->user->getId();
                $modulo->fecha_del = Utils::getFechaActual();
                $modulo->ipmaq_del = Utils::obtenerIP();

                if (!$modulo->save()) {
                    Utils::show($modulo->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro modulo");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($modulo->id_opcion);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista(){
        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length =  $perpage;

        try {
            $command = Yii::$app->db->createCommand('call listadoModulo(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();

            $padres = Opciones::find()->where(["flg_padre" => 1])->all();


        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        $flgpadre = 0;

        foreach ($result as $k => $row) {

            if ($row['flg_padre'] == 1) {
                
                $flgpadre = "";
            }

            else{

                if ($row['padre'] == 0 && $row['flg_padre'] == 0) {
                    
                    $flgpadre = "";
                }else{

                    foreach ($padres as $j => $ro) {

                        if ($ro['id_opcion'] == $row['padre']) {
                            
                            $flgpadre = $ro['nombre_opcion'];
                        }                
                    }  
                }     
            }


            $data[] = [
                "nombre" => $row['nombre_modulo'],
                "ruta" => $row['ruta'],
                "padre" => $flgpadre,
                "accion" => '<button class="btn  btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditarModulo(' . $row["id_modulo"] . ')"><i class="flaticon-edit"></i></button>
                             <button class="btn  btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminarModulo(' . $row["id_modulo"] . ')"><i class="flaticon-delete"></i></button>',
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
        Utils::jsonEncode($json_data);
    }

}
