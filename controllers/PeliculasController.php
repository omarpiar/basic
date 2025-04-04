<?php

namespace app\controllers;

use Yii;
use app\models\Peliculas;
use app\models\PeliculasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * PeliculasController implements the CRUD actions for Peliculas model.
 */
class PeliculasController extends Controller
{
    private $ftp_server = "appweb.somee.com";
    private $ftp_user = "opa010405";
    private $ftp_pass = "Pelucho.010405";
    private $ftp_path = "/www.appweb.somee.com/uploads/peliculas/";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@'], // Solo usuarios autenticados
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->user->loginRequired();
                    }
                    throw new \yii\web\ForbiddenHttpException('No tienes permiso para acceder a esta página');
                },
            ],
        ];
    }

    public function actionIndex()
    {
        try {
            $searchModel = new PeliculasSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionIndex: " . $e->getMessage(), 'peliculas');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al listar las películas');
            return $this->redirect(['site/index']);
        }
    }

    public function actionView($id)
    {
        try {
            //throw new \Exception("Este es un error de prueba");

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
            
        } catch (NotFoundHttpException $e) {
            Yii::error("Película no encontrada (actionView): " . $e->getMessage(), 'peliculas');
            Yii::$app->session->setFlash('error', 'La película solicitada no existe.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionView: " . $e->getMessage(), 'peliculas');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al mostrar la película.');
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        $model = new Peliculas();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->imagenFile = UploadedFile::getInstance($model, 'imagenFile');
                
                if ($model->validate()) {
                    // Subir imagen a FTP si existe
                    if ($model->imagenFile) {
                        $localPath = Yii::getAlias('@app') . '/uploads/' . $model->imagenFile->baseName . '.' . $model->imagenFile->extension;
                        
                        if (!$model->imagenFile->saveAs($localPath)) {
                            throw new \Exception("No se pudo guardar el archivo temporal");
                        }
                        
                        $remoteFileName = time() . '_' . $model->imagenFile->baseName . '.' . $model->imagenFile->extension;
                        $result = $this->uploadToFTP($localPath, $remoteFileName);
                        
                        if (strpos($result, '✅') === false) {
                            throw new \Exception($result);
                        }
                        
                        $model->Imagen = 'http://appweb.somee.com/uploads/peliculas/' . $remoteFileName;
                        unlink($localPath);
                    }
                    
                    if (!$model->save(false)) {
                        throw new \Exception("No se pudo guardar la película en la base de datos");
                    }
                    
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
            return $this->render('create', ['model' => $model]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionCreate: " . $e->getMessage(), 'peliculas');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            
            // Limpieza en caso de error
            if (isset($localPath) && file_exists($localPath)) {
                @unlink($localPath);
            }
            
            Yii::$app->session->setFlash('error', 'Ocurrió un error al crear la película: ' . $e->getMessage());
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->Imagen;

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->imagenFile = UploadedFile::getInstance($model, 'imagenFile');
                
                if ($model->validate()) {
                    // Manejo de la nueva imagen
                    if ($model->imagenFile) {
                        // Eliminar imagen anterior si existe
                        if ($oldImage) {
                            $this->deleteFromFTP($oldImage);
                        }
                        
                        // Subir nueva imagen
                        $localPath = Yii::getAlias('@app') . '/uploads/' . $model->imagenFile->baseName . '.' . $model->imagenFile->extension;
                        
                        if (!$model->imagenFile->saveAs($localPath)) {
                            throw new \Exception("No se pudo guardar el archivo temporal");
                        }
                        
                        $remoteFileName = time() . '_' . $model->imagenFile->baseName . '.' . $model->imagenFile->extension;
                        $result = $this->uploadToFTP($localPath, $remoteFileName);
                        
                        if (strpos($result, '✅') === false) {
                            throw new \Exception($result);
                        }
                        
                        $model->Imagen = 'http://appweb.somee.com/uploads/peliculas/' . $remoteFileName;
                        unlink($localPath);
                    }
                    
                    if (!$model->save(false)) {
                        throw new \Exception("No se pudo actualizar la película");
                    }
                    
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
            return $this->render('update', ['model' => $model]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionUpdate: " . $e->getMessage(), 'peliculas');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            
            // Limpieza en caso de error
            if (isset($localPath) && file_exists($localPath)) {
                @unlink($localPath);
            }
            
            Yii::$app->session->setFlash('error', 'Ocurrió un error al actualizar la película: ' . $e->getMessage());
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            
            // Eliminar imagen del servidor FTP si existe
            if ($model->Imagen) {
                if (!$this->deleteFromFTP($model->Imagen)) {
                    Yii::warning("No se pudo eliminar la imagen del FTP: " . $model->Imagen, 'peliculas');
                }
            }
            
            if (!$model->delete()) {
                throw new \Exception("No se pudo eliminar la película");
            }
            
            return $this->redirect(['index']);
            
        } catch (NotFoundHttpException $e) {
            Yii::error("Película no encontrada (actionDelete): " . $e->getMessage(), 'peliculas');
            Yii::$app->session->setFlash('error', 'La película que intentas eliminar no existe.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionDelete: " . $e->getMessage(), 'peliculas');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al eliminar la película.');
            return $this->redirect(['index']);
        }
    }

    private function uploadToFTP($localPath, $remoteFileName)
    {
        try {
            $ftp_conn = ftp_connect($this->ftp_server);
            if (!$ftp_conn) {
                throw new \Exception("No se pudo conectar al servidor FTP");
            }

            $login = ftp_login($ftp_conn, $this->ftp_user, $this->ftp_pass);
            if (!$login) {
                ftp_close($ftp_conn);
                throw new \Exception("Error al iniciar sesión en el servidor FTP");
            }

            ftp_pasv($ftp_conn, true);

            // Crear directorio si no existe
            if (!@ftp_chdir($ftp_conn, $this->ftp_path)) {
                ftp_mkdir($ftp_conn, $this->ftp_path);
            }

            $remotePath = $this->ftp_path . $remoteFileName;

            if (ftp_put($ftp_conn, $remotePath, $localPath, FTP_BINARY)) {
                ftp_close($ftp_conn);
                return "✅ Archivo subido correctamente.";
            } else {
                ftp_close($ftp_conn);
                throw new \Exception("Error al subir el archivo por FTP");
            }
            
        } catch (\Exception $e) {
            Yii::error("Error en uploadToFTP: " . $e->getMessage(), 'ftp');
            return "❌ Error FTP: " . $e->getMessage();
        }
    }

    private function deleteFromFTP($imageUrl)
    {
        try {
            $ftp_conn = ftp_connect($this->ftp_server);
            if (!$ftp_conn) {
                throw new \Exception("No se pudo conectar al servidor FTP");
            }

            $login = ftp_login($ftp_conn, $this->ftp_user, $this->ftp_pass);
            if (!$login) {
                ftp_close($ftp_conn);
                throw new \Exception("Error al iniciar sesión en el servidor FTP");
            }

            ftp_pasv($ftp_conn, true);

            $fileName = basename($imageUrl);
            $remotePath = $this->ftp_path . $fileName;

            $result = ftp_delete($ftp_conn, $remotePath);
            ftp_close($ftp_conn);

            return $result;
            
        } catch (\Exception $e) {
            Yii::error("Error en deleteFromFTP: " . $e->getMessage(), 'ftp');
            return false;
        }
    }

    protected function findModel($id)
    {
        if (($model = Peliculas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La película solicitada no existe.');
    }
}