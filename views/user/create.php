<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Useradmin */

$this->title = 'Администрирование пользователей(Добавление)';
$this->params['breadcrumbs'][] = ['label' => 'Администрирование пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="useradmin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles
    ]) ?>

</div>
