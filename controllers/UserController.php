<?php

namespace app\controllers;

use Yii;
use app\models\Useradmin;
use app\models\UserSearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use app\models\Menu;

/**
 * UserController implements the CRUD actions for Useradmin model.
 */
class UserController extends Controller
{

    public $catalogMenu;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function init() {
        $this->catalogMenu = Menu::getStructure();
        parent::init();
    }

    public function actionGogome()
    {
		return $this->redirect('site/index');
	}

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

	    return $this->render('index', [
    	    'searchModel' => $searchModel,
	        'dataProvider' => $dataProvider,
    	]);
    }

    public function actionView($id)
    {
		$model = new UserAdmin();
		$query = Useradmin::find()
					->select('users.*, auth_item.name as role_name, auth_item.description as role_description')
					->leftjoin('auth_Assignment','`auth_Assignment`.`user_id` = `users`.`id`')
					->leftjoin('auth_item','`auth_item`.`name` = `auth_Assignment`.`item_name`')
					->where(['users.id' => $id])  
					->asArray()
					->one();

		$model->load($query);
		$model->attributes = $query;

        $modelAA = authItem::find()->one();

        return $this->render('view', [
            'model' => $model,
            'modelAA' => $modelAA,
            'modelAI' => $modelAI
        ]);
    }

    public function actionCreate()
    {
        $model = new Useradmin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
						$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
//var_dump($model);die();
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
//			die();
            if($model->save()){
			
						$url = Yii::$app->session['filter'];
						Yii::$app->session['filter']="";

						return $this->redirect([$url, 'id' => $model->id]);
			}
			else{
			    die('Cannot save!');
			}
        } 
		else 
		{
//			die('!!!');
		/*	$query = UserAdmin::find()
					->select('users.*, users.id as id, users.name as name, users.phone, auth_item.name as role, auth_item.name as role_name, , auth_item.description as role_description')
					->leftjoin('auth_Assignment','`auth_Assignment`.`user_id` = `users`.`id`')
					->leftjoin('auth_item','`auth_item`.`name` = `auth_Assignment`.`item_name`')
					->where(['users.id' => $id])  
					->asArray()
					->one();

					$model->setAttributes($query);

//					var_dump($model);die();
                           */
			        return $this->render('update', [
		                'model' => $model,
        		    ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

				if(Yii::$app->session['filter']!=NULL){
						$url = Yii::$app->session['filter'];
						Yii::$app->session['filter']="";
					return $this->redirect($url);
				}else{
			        return $this->redirect(['index']);
				}
    }

    protected function findModel($id)
    {
        if (($model = Useradmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
