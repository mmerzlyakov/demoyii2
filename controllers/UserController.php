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
use app\models\AuthAssignment;
use app\models\AuthItem;

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
/*		$model = new UserAdmin();
		$query = Useradmin::find()
					->select('users.*, auth_item.name as role_name, auth_item.description as role_description')
					->leftjoin('auth_Assignment','`auth_Assignment`.`user_id` = `users`.`id`')
					->leftjoin('auth_item','`auth_item`.`name` = `auth_Assignment`.`item_name`')
					->where(['users.id' => $id])  
					->asArray()
					->one();

		$model->load($query);

        $model->attributes = $query;
*/
        $model = $this->findModel($id);

        //$roles = Yii::$app->authManager->roles;
        $model->role = \Yii::$app->authManager->getRolesByUser($id);

        if(empty($model->role))
            $model->role = 'Пользователь';
        else
            $model->role = $model->role[key($model->role)]->description;

        return $this->render('view', [
            'model' => $model,
            //'role' => $role,
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

        $roles = Yii::$app->authManager->roles;
        $model->role = \Yii::$app->authManager->getRolesByUser($id);

        if(empty($model->role))
            $model->role = ['name'=>'user', 'description' => 'Пользователь'];
        else
            $model->role = $model->role[key($model->role)]->name;


        if ($model->load(Yii::$app->request->post())){
            if(isset($model->password) && !empty($model->password)){
                $user = User::findById($id);
                $user->setPassword($model->password);

                $user->save();
            }

             $auth = Yii::$app->authManager;
            $role = $auth->getRole($model->role);
            $auth->revokeAll($id);
            $auth->assign($role,$id);

           // var_dump($model);die();

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'roles' => $roles,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $roles,
            ]);
        }
    }

    public function action1Update1($id)
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
