<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\nav\NavX;
use yii\db\Query;
use yii\db\ActiveDataProvider;
use app\models\Menu;


/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
     </div>

    <div class="body-content">
 
		<?php
/*
        echo "<pre>";
print_r($list);
        echo "</pre>";*/

        echo Nav::widget(['items' => Menu::getStructure(10000006, true)]);


		?>

    </div>
</div>