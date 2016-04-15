<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Useradmin */

$this->title = 'Администрирование пользователей (редактирование): ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Администрирование пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

//var_dump($_SESSION['filter']);
//die();


?>
<div class="useradmin-update">



    <h1><?= Html::encode($model->id.": ".$model->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
