<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\widgets\Pjax;
use yii\db\Query;
use yii\base\Security;



class SiteController extends Controller
{
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


		$list = $this->getStructure(10000006,true);

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
            return json_encode(array('flag' => true, 'username' => Yii::$app->user->identity->name));
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

    public function getStructure($category = 0, $flag = false)
    {
        $fl = $flag;

        if($category==0) {
            $result = (new \yii\db\ActiveRecord())
                ->find()
                ->from('category')
                ->where('')
                ->orderby('sort ASC')
                ->asarray()
                ->all();
        }
        else{
            $result = (new \yii\db\ActiveRecord())
                    ->find()
                    ->from('category')
                    ->where('category.id = '.$category)
                    ->orWhere('category.parent_id = '.$category)
                    ->orderby('sort ASC')
                    ->asarray()
                    ->all();

        }

//  var_dump($result);
//  $result = Category::find()->asArray()->all();

        if (!$result) {     return NULL;  }

        // $arr_cat будет создаваться массив категорий, где индексы, это parent_id
        $arr_cat = array();

        //В цикле формируем массив
        for ($i = 0; $i < count($result);$i++) {
            $row = $result[$i];
            if ($row['parent_id'] == null) {
                $row['parent_id'] = 0;
                //$row['count'] = 0;
            }
            //Формируем массив, где ключами являются id родительской категории
            if (empty($arr_cat[$row['parent_id']])) {
                $arr_cat[$row['parent_id']] = array();
                //$arr_cat[$row['count']] = 0;
            }

            $arr_cat[$row['parent_id']][] = $row;
            //$arr_cat[$row['count']][] = count($row);
        }

// $view_cat - лямда функция для создания массива категорий, который будет передан в отображение
        $view_cat =

            function ($data, $parent_id = 0) use ( & $view_cat, & $fl){

                $result = NULL;
                if (empty($data[$parent_id])) {
                    return;
                }

                $result = array();


                //перебираем в цикле массив и выводим на экран
                for ($i = 0; $i < count($data[$parent_id]);$i++) {
                    if($i==0 && $fl==true){
                    $result[] = ['label' => $data[$parent_id][$i]['title']."(".count($data[$parent_id]).")",
                                 'url' => 'catalog/'.$data[$parent_id][$i]['alias'].'/'.$data[$parent_id][$i]['id'],
                                 'alias' => $data[$parent_id][$i]['alias'],
                                 //можно пометить какой либо пункт как активный
                                 'active' => $data[$parent_id][$i]['id'] == 8,

                                 'options' => ['class' => 'dropdown' ],
                                 'items' => $view_cat($data,$data[$parent_id][$i]['id']),];
                    }else{
                        $result[] = ['label' => $data[$parent_id][$i]['title'],
                                     'url' => 'catalog/'.$data[$parent_id][$i]['alias'].'/'.$data[$parent_id][$i]['id'],
                                     'alias' => $data[$parent_id][$i]['alias'],
                                     //можно пометить какой либо пункт как активный
                                     'active' => $data[$parent_id][$i]['id'] == 8,

                                     'options' => ['class' => 'dropdown' ],
                                     'items' => $view_cat($data,$data[$parent_id][$i]['id']),];
                    }

                    //рекурсия - проверяем нет ли дочерних категорий

                }
                return $result;
            };

        $result = $view_cat($arr_cat);
        return $result;
    }

    /*
     *
     *
     *  28/03/2016 by predator_pc
     *
     *
     * */

   public function forCms(){

    $query = new Query;

    $chain_groups = (new yii\db\Query())
    ->select(['id','name','url'])
    ->from('tags_chains_groups')
    ->where(['status' => 1])
    ->distinct()
    ->all();

    $links = (new yii\db\Query())
    ->select(['id','chain_id','tag_id',])
    ->from('tags_chains_links')
    ->where(['status' => 1])
    ->groupBy(['tag_id'])
    ->distinct()
    ->all();

    $links_count = (new yii\db\Query())
    ->select(['id','chain_id','tag_id',])
    ->from('tags_chains_links')
    ->where(['status' => 1])
    ->groupBy(['tag_id'])
    ->having('COUNT(tag_id) > 2')
    ->distinct()
    ->all();


    $chains = (new yii\db\Query())
    ->select(['id','group_id','name',])
    ->from('tags_chains')
    ->where(['status' => 1])
    ->distinct()
    ->all();

    $tags = (new yii\db\Query())
    ->select(['id','group_id','value',])
    ->from('tags')
    ->where(['status' => 1])
    ->andWhere(['group_id' => 1011])
    ->distinct()
    ->all();

/*    $tags = (new yii\db\Query())
    ->select(['id','group_id','value',])
    ->from('tags')
    ->where(['status' => 1])
    ->andWhere(['group_id' => 1011])
    ->distinct()
    ->all();
*/
    $aval=0; $current_url=""; $current_group_id=""; $curr_cat=array();
   //    var_dump($tags);
     //  die();

	$list = array();
	$items['items'] = array();
	$lc = 0;

	for($i=0; $i<count($chain_groups); $i++){

		$list[$lc]['item'] = $chain_groups[$i]['name'];
		$list[$lc]['level'] = 1;
        $list[$lc]['url'] = $chain_groups[$i]['url'];
        $list[$lc]['id'] = $chain_groups[$i]['id'];

		$items[$lc]['label']= $chain_groups[$i]['name'];
        $items[$lc]['url'] = $chain_groups[$i]['url'];
        $items[$lc]['id'] = $chain_groups[$i]['id'];

        $current_url = $chain_groups[$i]['url'];
        $lc++;

		for($j=0; $j<count($chains); $j++){
		      if($chains[$j]['group_id']==$chain_groups[$i]['id']){
				for($c=0; $c<count($links); $c++){
				      if($links[$c]['chain_id']==$chains[$j]['id']){

                          $current_group_id = $chains[$j]['id'];

							for($k=0; $k<count($tags); $k++){
							      if($tags[$k]['id']==$links[$c]['tag_id']) {
                                    for($a=0; $a<count($links_count); $a++){
	                                      	if($tags[$k]['id']==$links_count[$a]['tag_id']){
                            					$aval=1;



							    				$list[$lc]['item'] = $tags[$k]['value'];
								    			$list[$lc]['level'] = 2;
                                                $list[$lc]['url'] = $current_url;
                                                //$list[$lc]['id'] = $tags[$k]['id'];
                                                $list[$lc]['id'] = $current_group_id;

										$items[$lc]['items'] = [
												'label' => $tags[$k]['value'],
								         		'url' => $current_url,
										         'id' => $current_group_id];	
												$curr_cat = $items[$lc]['items'];

                                                $lc++;

                                                break 2;

										    }
										    else{
    											$aval=0;
	    									}
									}

                                   	if($aval==1){
                                        $aval=0;
    						        }
									else{

										$list[$lc]['item'] = $tags[$k]['value'];
										$list[$lc]['level'] = 3;
                                        $list[$lc]['id'] = $tags[$k]['id'];
                                        $list[$lc]['url'] = $current_url;

										$items[$lc]['items'] = [
												'label' => $tags[$k]['value'],
								         		'url' => $current_url,
										         'id' => $current_group_id,
												'items' => $curr_cat];	
                                        $lc++;
									}

								}
							}
					}
				}
			}
		}  
	}


//       var_dump($items);
//      die();


     //  var_dump($list);
     //  die();

		return $list;
//	return $items;

   }


}

