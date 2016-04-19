<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var app\models\gbUser $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<div id="success"> </div> <!-- For success message -->


<div class="site-signup">
   <h1>Вход</h1>

<div class="gb-user-form">


    <?php $form = ActiveForm::begin(['options' => ['class' => 'login-form']]); ?>

    <?php //= $form->field($model, 'username')->textInput(['maxlength' => 255], ['class' => 'input-modal']) ?>

    <?php //= $form->field($model, 'phone')->widget(MaskedInput::className(), ['mask' => '+79999999999',]) ?>

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td><img src="/images/7.gif"></td>
            <td><?= $form->field($model, 'phone')->textInput(['maxlength' => 10], ['autofocus' => true], ['class' => 'input-modal', 'id' => 'phone'])->label('Номер телефона') ?></td>
        </tr>
    </table>


    

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255], ['class' => 'input-modal'])->label('Пароль') ?>

    <?= $form->field($model, 'rememberMe')->checkbox(['label' => ''])->label('Запомнить меня') ?>

    <div class="form-group text-right">
        <?= Html::submitButton('Вход', ['class' => 'btn btn-success btn-update-password']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div> 

</div>