<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use app\models\User;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">

        <p class="lead">Authorization by predator_pc@10/04/2016</p>

    </div>

    <div class="body-content">

<?php
             

echo Nav::widget([
                'options' => ['id' => 'topnav','class' => 'navbar-default center-block text-center'],//.navbar-nav
                'items' => [
                    ['label' => 'О нас', 'url' => ['/site/about']],
                    ['label' => 'История', 'url' => ['/site/history']],
                    ['label' => 'Деятельность', 'url' => ['/site/activities'],
          'options' => ['id' => 'down_menu'],			
          'items' => [
                      ['label' => 'Наши собаки', 'url' => ['/site/history'],'options' => ['id' => 'down_history']],
                      ['label' => 'Наши волонтёры', 'url' => ['/site/event'],'options' => ['id' => 'wn_history']],
          ['label' => 'Реабилитация', 'url' => ['/activities/reabilitation'],'options' => ['id' => 'n_history']],
                      ['label' => 'Спонсорам и партнёрам', 'url' => ['/activities/sponsor']],]],
                    ['label' => 'Мероприятия', 'url' => ['/site/event']],
                    ['label' => 'СМИ о нас', 'url' => ['/site/smi']],
                ],
            ]);

//3 level

	$iz=array();
	$iz['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$iz['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$iz['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$iz['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];

	$ix=array();
	$ix['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$ix['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$ix['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];
	$ix['items'][] = [ 'label' => 'Fuckoff3', 'url' => '/'];

//2 level
	$iw=array();
	$iw['items'][] = [ 'label' => 'Fuckoff2', 'url' => '/', 'items' => $iz['items']];
	$iw['items'][] = [ 'label' => 'Fuckoff2', 'url' => '/', 'items' => $ix['items']];

	$is=array();
	$is['items'][] = [ 'label' => 'Fuckoff1', 'url' => '/', 'items' => $ix['items']];
	$is['items'][] = [ 'label' => 'Fuckoff1', 'url' => '/', 'items' => $iz['items']];

//1 level

	$it=array();
	$it['items'][] = [ 'label' => 'FuckoffA', 'url' => '/', 'items' => $iw['items']];
	$it['items'][] = [ 'label' => 'FuckoffB', 'url' => '/', 'items' => $iw['items']];
	$it['items'][] = [ 'label' => 'FuckoffC', 'url' => '/', 'items' => $is['items']];
	$it['items'][] = [ 'label' => 'FuckoffD', 'url' => '/', 'items' => $is['items']];

//top

	$items = array();
	$items['items'] = $it['items'];
//	$items['items'][] = $ir['items']; 
//	$items['items'][] = ['label'=>'All', 'url'=>['/shop/products/index']];


echo Menu::widget($items);

echo Nav::widget($items);


?>



    </div>
</div>