<?php

namespace app\controllers;

use Yii;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Usuarios models.
     * @return string
     */
    public function actionIndex()
    {
        try {
            $searchModel = new UsuariosSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionIndex: " . $e->getMessage(), 'usuarios');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al listar los usuarios');
            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Usuarios model.
     * @param int $id ID
     * @return string
     */
    public function actionView($id)
    {
        try {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
            
        } catch (NotFoundHttpException $e) {
            Yii::error("Usuario no encontrado (actionView): " . $e->getMessage(), 'usuarios');
            Yii::$app->session->setFlash('error', 'El usuario solicitado no existe.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionView: " . $e->getMessage(), 'usuarios');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al mostrar el usuario.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Creates a new Usuarios model.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Usuarios();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        throw new \Exception("No se pudo guardar el usuario: " . json_encode($model->errors));
                    }
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionCreate: " . $e->getMessage(), 'usuarios');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing Usuarios model.
     * @param int $id ID
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        try {
            if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    throw new \Exception("No se pudo actualizar el usuario: " . json_encode($model->errors));
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
            
        } catch (NotFoundHttpException $e) {
            Yii::error("Usuario no encontrado (actionUpdate): " . $e->getMessage(), 'usuarios');
            Yii::$app->session->setFlash('error', 'El usuario que intentas actualizar no existe.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionUpdate: " . $e->getMessage(), 'usuarios');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage());
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Usuarios model.
     * @param int $id ID
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            
            if (!$model->delete()) {
                throw new \Exception("No se pudo eliminar el usuario");
            }
            
            return $this->redirect(['index']);
            
        } catch (NotFoundHttpException $e) {
            Yii::error("Usuario no encontrado (actionDelete): " . $e->getMessage(), 'usuarios');
            Yii::$app->session->setFlash('error', 'El usuario que intentas eliminar no existe.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionDelete: " . $e->getMessage(), 'usuarios');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al eliminar el usuario.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * @param int $id ID
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El usuario solicitado no existe.');
    }
}