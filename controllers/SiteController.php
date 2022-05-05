<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Supplier;
use app\models\SupplierSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use kartik\grid\GridView as KGridView;

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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays supplier gridview simple page.
     *
     * @return string
     */
    public function actionSupplier()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Supplier::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        try {
            $table = GridView::widget([
                'dataProvider' => $dataProvider,

                'columns' => [
                    'id',
                    'name',
                    'code',
                    't_status',
                ]
            ]);
        } catch(\Exception $e) {
            Yii::warning("somthing wrong:". $e->getMessage());
        }
        return $this->render('supplier', ['table' => $table]);
    }

    /**
     * Displays supplier gridview search page.
     *
     * @return string
     */
    public function actionSupplier2()
    {
		$searchModel  = new SupplierSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        try {
            $table = GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'showFooter' => false,
                'columns' => [
                    /*
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                     */
                    'id',
                    'name',
                    [
                        'attribute' => 'code',
                        //'headerOptions' => ['style' => 'text-align:center;'],
                        //'enableSorting' => false,
                        //'contentOptions' => ['style' => 'background-color:gray;visibility:hidden;']
                    ],
                    [
                        'attribute' => 't_status',
                        'label'     => 'Status',
                        'value'     => ['app\models\SupplierUtil', 'getTStatusValue'],
                        'contentOptions' => function($data) {
                            if ($data->t_status == 'ok') {
                                return ['style' => 'color:green'];
                            }
                            return ['style' => 'color:gray'];
                        },
                        'filter'   => SupplierSearch::$allStatus,
                        'filterInputOptions' => [
                            'class'  => 'form-control',
                            'id'     => null,
                            'prompt' => '全部',
                        ],
                        'footer'   => 'Footer',
                    ],
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'header'   => 'Action',
                        'headerOptions' => ['style' => 'width:120px;color:red'],
                        //'template' => '{view} {update} {delete}',
                    ],
                ],
                'layout'      => "\n{summary}\n{items}\n{pager}",
                'showOnEmpty' => true,
                //'emptyCell'   => 'not set header',
            ]);
        } catch(\Exception $e) {
            Yii::warning("somthing wrong:". $e->getMessage());
        }
        return $this->render('supplier2', ['table' => $table]);
    }

    /**
     * Displays supplier gridview export page.
     *
     * @return string
     */
    public function actionSupplier3()
    {
        $searchModel  = new SupplierSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns  = [
            'id',
            'name',
            'code',
            't_status',
        ];

        $menu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns'      => $gridColumns,
        ]);

        $table = KGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'pjax'         => true,
            'pager'        => [
                'firstPageLabel' => "首页",
                'prevPageLabel'  => '上一页',
                'nextPageLabel'  => '下一页',
                'lastPageLabel'  => '未页',
            ],
            'columns'     => $gridColumns,
            'layout'      => "\n{summary}\n{items}\n{pager}",
            'showOnEmpty' => true,
        ]);

        return $this->render('supplier3', ['menu' => $menu, 'table' => $table]);
    }
}
