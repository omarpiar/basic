<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     * @return string
     */
    public function actionIndex()
    {
        try {
            $searchModel = new \app\models\PeliculasSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionIndex: " . $e->getMessage(), 'site');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al cargar la página principal');
            return $this->render('index', [
                'searchModel' => null,
                'dataProvider' => null,
            ]);
        }
    }

    /**
     * Login action.
     * @return Response|string
     */
    public function actionLogin()
    {
        try {
            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->login()) {
                    return $this->redirect(['/peliculas/index']);
                } else {
                    Yii::error("Error de login para usuario: " . $model->username, 'auth');
                }
            }

            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionLogin: " . $e->getMessage(), 'auth');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error durante el login');
            return $this->redirect(['index']);
        }
    }

    /**
     * Logout action.
     * @return Response
     */
    public function actionLogout()
    {
        try {
            Yii::$app->user->logout();
            return $this->goHome();
            
        } catch (\Exception $e) {
            Yii::error("Error en actionLogout: " . $e->getMessage(), 'auth');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al cerrar sesión');
            return $this->goHome();
        }
    }

    /**
     * Displays contact page.
     * @return Response|string
     */
    public function actionContact()
    {
        try {
            $model = new ContactForm();
            
            if ($model->load(Yii::$app->request->post())) {
                if ($model->contact(Yii::$app->params['adminEmail'])) {
                    Yii::$app->session->setFlash('success', 'Gracias por contactarnos. Te responderemos a la brevedad.');
                    return $this->refresh();
                } else {
                    Yii::error("Error al enviar formulario de contacto: " . json_encode($model->errors), 'contact');
                }
            }
            
            return $this->render('contact', [
                'model' => $model,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionContact: " . $e->getMessage(), 'contact');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al enviar el formulario de contacto');
            return $this->refresh();
        }
    }

    /**
     * Displays cartelera page.
     * @return string
     */
    public function actionCartelera()
    {
        try {
            $searchModel = new \app\models\PeliculasSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            
        } catch (\Exception $e) {
            Yii::error("Error en actionCartelera: " . $e->getMessage(), 'site');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al cargar la cartelera');
            return $this->render('index', [
                'searchModel' => null,
                'dataProvider' => null,
            ]);
        }
    }

    /**
     * Displays about page.
     * @return string
     */
    public function actionAbout()
    {
        try {
            return $this->render('about');
            
        } catch (\Exception $e) {
            Yii::error("Error en actionAbout: " . $e->getMessage(), 'site');
            \app\controllers\MailServiceController::sendErrorEmail($e);
            Yii::$app->session->setFlash('error', 'Ocurrió un error al cargar la página de información');
            return $this->redirect(['index']);
        }
    }
}