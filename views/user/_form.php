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

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 10], ['autofocus' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput()->label('Новый пароль')->hint('Оставьте без изменений, если не нужно менять') ?>


    <?php //= $form->field($model, 'role')->DropDownList(['' => $val, 'options' => [ $listV['role_description'] => ['selected ' => true]]] )  ?>

	<?php // var_dump($roles); die(); ?>

    <?= $form->field($model, 'role')->DropDownList(ArrayHelper::map($roles,'name','description'))  ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
