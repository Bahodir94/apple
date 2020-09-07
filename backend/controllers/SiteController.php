<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Apples;
use backend\models\ApplesSearch;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'create', 'eat', 'fall'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ApplesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()){
            $count = Yii::$app->request->post('count');
            for ($i=0; $i<$count; $i++){
                $model = new Apples();
                $colors = ['green','black','yellow','red','blue'];
                $color = rand(0,count($colors)-1);
                $model->color = $colors[$color];
                $model->date_of_apperance = date('U');
                $model->status = 1;
                $model->size = 100;
                $model->save();
            }
            return $this->redirect(['index']);

        }
    }

    public function actionEat($id)
    {
        $model = $this->findModel($id);
        $size = $model->size;
        if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
            $model->size = $size - $model->size;
            if ($model->status == 0 && $model->date_of_fall!='' && date('U') - $model->date_of_fall <= 18000)
                $model->save();
            return $this->redirect(['index']);
        }
        return $this->renderAjax('eat', [
            'model' => $model,
        ]);
    }

    public function actionFall($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->date_of_fall = date('U');
        if ($model->save())
            return $this->redirect(['index']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    protected function findModel($id)
    {
        if (($model = Apples::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
