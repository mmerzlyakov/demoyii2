<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\nav\NavX;
use yii\db\Query;
use yii\db\ActiveDataProvider;


/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
     </div>

    <div class="body-content">
 
		<?php



        NavBar::begin([
//        'brandLabel' => 'My Company',
//        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-default'],
        ]);

        echo Menu::widget(['items' => $list]);


        NavBar::end();



		?>

    </div>
</div>