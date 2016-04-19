<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Useradmin;
use app\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\Useradmin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="useradmin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['rows' => 6]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput()->label('Новый пароль')->hint('Оставьте без изменений, если не нужно менять') ?>

	<?php 
				$listV = Useradmin::find()
					->select([
					'id',
					'auth_Assignment.user_id as user_id',
					'auth_item.name as role_name',
					'auth_item.name as role',
					'auth_item.description as role_description'
					])
					->leftjoin('auth_Assignment','auth_Assignment.user_id = users.id')
					->leftjoin('auth_item','auth_item.name = auth_Assignment.item_name')
					->where(['auth_Assignment.user_id' => $model->id])
						->groupby('role_name')
						->asarray()
						->one();
//					->all()

//var_dump($listV);

				$list =  (new yii\db\Query())
					->select([
//					'user_id',
//					'auth_Assignment.user_id as user_id',
					'auth_item.name as role_name',
					'auth_item.description as role_description'
					])
					->from('auth_item')
					->leftjoin('auth_Assignment','auth_Assignment.item_name = auth_item.name')
//					->leftjoin('auth_item','auth_item.name = auth_Assignment.item_name')
					->where(['auth_item.type' => 1])
						->groupby('role_name')
//						->asArray()
//						->one();
					->all();

		//				$list['role_description']

//	var_dump($list);
//	die();

/*
				if(empty($sql))
						return 'Пользователь';

				$response = $sql['role_description'];

				if($sql['role_name']!='user')
	                return $response;
	    */

	$val = array(); $c=0;

	foreach($list as $key) {
			$val[$c] = $key['role_description'];
//			$val[1] = 'selected';
			$c++;
		}


//var_dump($model);
//var_dump($val);
//var_dump($model->role_description);
//var_dump($listV['role_description']);
//die();

//ПОПРОБОВАТЬ КВЕРИ ИЗ БАЗЫ НА ЭТИ ПОЛЯ И ПРИСУНУТЬ ИХ ТУДА
	$authors = AuthItem::find()
					->select([
//					'id',
//					'auth_Assignment.user_id as user_id',
//					'auth_item.name as role_name',
//					'auth_item.description as role_description'
					'name','description'
					])
//					->leftjoin('auth_Assignment','auth_Assignment.item_name =auth_item.item_name')
//					->leftjoin('auth_item','auth_item.name = auth_Assignment.item_name')
//					->where(['auth_Assignment.user_id' => $model->id])
					->where(['type' => 1])
					->groupby('name')
					->all();


//var_dump($model);

//	where(['id' => $model->id])->all();
// формируем массив, с ключем равным полю 'id' и значением равным полю 'name' 
    $items = ArrayHelper::map($authors,'description','description');
    $params = [
        'prompt' => 'Выбрать категорию'
    ];

	?>

    <?php //= $form->field($model, 'role')->DropDownList(['' => $val, 'options' => [ $listV['role_description'] => ['selected ' => true]]] )  ?>
    <?= $form->field($model, 'role_description')->DropDownList($items, $params)  ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
