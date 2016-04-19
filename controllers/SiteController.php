<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\Menu;
use app\models\SignupForm;
use Yii;
use yii\base\Security;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\widgets\Pjax;


class SiteController extends Controller
{

    public $catalogMenu;

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

    public function init() {
        $this->catalogMenu = Menu::getStructure();
        parent::init();
    }

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

    public function actionIndex()
    {
        $model = new LoginForm();


		//$list = Yii::$app->controller->catalogMenu;
        $list = Menu::getStructure(10000006,true);

        return $this->render('index', [
            	'model'=> $model,
				'list' => $list,
		]);
    }


	public function actionLogin()
    {
        $model = new LoginForm();
        return $this->renderPartial('login', [
            'model' => $model,
        ]);
    }

	public function actionSubmitlogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post());
     
      	if($model->login()){ 
            //save the password
            return json_encode(array('flag' => true, 'username' => Yii::$app->user->identity->name, 'phone' => Yii::$app->user->identity->phone));
        }
        else
        {
            return $this->renderPartial('login', [
            'model' => $model,
            ]);
        }
    }
    

	public function actionSignup()
    {
        $model = new SignupForm();
        return $this->renderPartial('signup', [
            'model' => $model,
        ]);
    }

	public function actionSubmitsignup()
    {

        $model = new SignupForm();
        $model->load(Yii::$app->request->post());

      	if($user = $model->signup()){ 
				if(Yii::$app->getUser()->login($user)){
				        return json_encode(array('flag' => true, 'username' => Yii::$app->user->identity->name));
				}
        }
        else
        {
            return $this->renderPartial('signup', [            'model' => $model,            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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

    public function actionAbout()
    {
        return $this->render('about');
    }

	public function actionFormSubmission()
    {
        $security = new Security();
        $string = Yii::$app->request->post('string');
        $stringHash = '';
        if (!is_null($string)) {
            $stringHash = $security->generatePasswordHash($string);
        }
        return $this->render('index', [
            'stringHash' => $stringHash,
        ]);
    }

}

