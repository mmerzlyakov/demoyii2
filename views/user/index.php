<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\SignupForm;
use app\models\UserTableForm;
use app\models\UserAdmin;
use yii\data\ActiveDataProvider;
use yii\rbac\Rule;
use app\models\AuthItem;
use yii\db\ActiveRecord;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Администрирование пользователей';
$this->params['breadcrumbs'][] = ['label' => "Домой", 'url' => ['/'], ['id' => '1']];
$this->params['breadcrumbs'][] = $this->title;

if(!Yii::$app->user->identity)
{
	return Yii::$app->controller->Gohome();
}

if(Yii::$app->user->can('GodMode'))
{

Yii::$app->session['filter']=$_SERVER['REQUEST_URI'];

?>
<div class="useradmin-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить Пользователя +', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Роли', ['roles'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Разрешения', ['permissions'], ['class' => 'btn btn-success']) ?>
    </p>

<?php Pjax::begin(); ?>

 <?php 

	$authors = AuthItem::find()
					->select([
					'name','description'
					])
					->where(['type' => 1])
					->groupby('name')
					->all();

//var_dump($model);
//	where(['id' => $model->id])->all();

// формируем массив, с ключем равным полю 'id' и значением равным полю 'name' 
    $items = ArrayHelper::map($authors,'name','description');
    $params = [
        'prompt' => 'Выбрать категорию'
    ];


echo	GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//		'layout' =>"{sorter}\n{pager}\n{summary}\n{items}",
        'columns' => [
	        ['class' => 'yii\grid\SerialColumn'],
//         	'id', 'name','fullname',
		 	[
			'class' => 'yii\grid\DataColumn', // this line is optional
		    'attribute' => 'id',
			'header' => 'ID', 
			'value' => 'id',
//			'format' => 'raw',
			'contentOptions' => ['style' => 'width: 10px;'],

			],
            'phone',
		 	[	
//			'header' => 'Login',
			'attribute' => 'name',
//			'value' => 'name',
			'label' => 'ФИО',
			'format'=>'raw',
			'value' => function ($data, $url, $model){
//        		           return Html::a($data['name'],"index.php?r=user/update&id=".$url);
        		           return Html::a($data['name'],"/user/update/".$url);
			}, 
//			'contentOptions' => ['style' => 'width: 10px;'],
		],

	  	  [
            'attribute'=>'role_name',
            'label'=>'Первичная роль',
            'format'=>'text', // Возможные варианты: raw, html
            'filter' => $items,
			'content' => function($data, $model){
				$sql = Useradmin::find()
					->select([
					'id',
					'auth_Assignment.user_id as user_id',
					'auth_item.name as role_name',
					'auth_item.description as role_description'
					])
					->leftjoin('auth_Assignment','auth_Assignment.user_id = users.id')
					->leftjoin('auth_item','auth_item.name = auth_Assignment.item_name')
					->where(['auth_Assignment.user_id' => $model])
//					->orderby('id')
					->asarray()
					->one();

					if(empty($sql))
						return 'Пользователь';
				$response = $sql['role_description'];

				if($sql['role_name']!='user')
	                return $response;
				},
        ],

           ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 

 Pjax::end(); 

}
else {
?>

<h1>Недостаточно привелегий!</h1>

<?php

}
?>
</div>
