<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>


<div class="site-signup">
   <h1>Регистрация</h1>

<div class="gb-user-form">


    <?php $form = ActiveForm::begin(['options' => ['class' => 'signup-form']]); ?>

                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td><img src="/images/7.gif"></td>
                        <td><?= $form->field($model, 'phone')->textInput(['maxlength' => 10], ['autofocus' => true])->label('Номер телефона') ?></td>
                    </tr>
                </table>


                <?= $form->field($model, 'name')->label('Ваше имя') ?>

                <?= $form->field($model, 'email')->input('email')->label('E-mail') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), 
					[
	                  'template' => '{image}<br>{input}',  
					]) ->label('Проверочный код')
				?>

			    <div class="form-group text-right">
			        <?= Html::submitButton('Вход', ['class' => 'btn btn-success btn-update-password']) ?>
			    </div>


            <?php ActiveForm::end(); ?>
        </div>
</div>

