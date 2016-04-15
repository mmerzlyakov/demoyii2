<?php Pjax::begin();  ?>
<?= Html::beginForm(['site/login'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>

    <?= Html::activeinput('text', $model, 'username') ?>
	<br>
    <?= Html::activeinput('text', $model, 'password') ?>
	<br>
    <?php // = Html::activecheckbox('ѕароль', 'rememberme', Yii::$app->request->post('rememberme'), ['class' => 'form-control']) ?>
    <?= Html::activecheckbox($model, 'rememberMe', ['class' => 'form-control']) ?>
	<br>
    <?= Html::submitButton('¬ход', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']) ?>

<?= Html::endForm() ?>

<?php Pjax::end(); ?> 
