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

	/*
	           

 function getStructure()
 {

$result = (new \yii\db\ActiveRecord())
	->find()
	->from('category')
	->where('')
	->orderby('sort ASC')
	->asarray()
	->all();

//var_dump($result);

//  $result = Category::find()->asArray()->all();

  if (!$result) {     return NULL;  }

  // $arr_cat будет создаватьс€ массив категорий, где индексы, это parent_id
  $arr_cat = array();

  //¬ цикле формируем массив
  for ($i = 0; $i < count($result);$i++) {
			   $row = $result[$i];
			   if ($row['parent_id'] == NULL)
					   $row['parent_id'] = 0;
			   //‘ормируем массив, где ключами €вл€ютс€ id родительской категории
			   if (empty($arr_cat[$row['parent_id']])) 
					    $arr_cat[$row['parent_id']] = array();

		   $arr_cat[$row['parent_id']][] = $row;
  }

// $view_cat - л€мда функци€ дл€ создани€ массива категорий, который будет передан в отображение
  $view_cat =

  function ($data, $parent_id = 0) use ( & $view_cat){

   $result = NULL;
   if (empty($data[$parent_id])) {
		   return;
   }

   $result = array();

   //перебираем в цикле массив и выводим на экран
   for ($i = 0; $i < count($data[$parent_id]);$i++) {
    $result[] = ['label' => $data[$parent_id][$i]['title'],
     'url' => 'assortiment/'.$data[$parent_id][$i]['id'],
      //можно пометить какой либо пункт как активный     
     'active' => $data[$parent_id][$i]['id'] == 8,
     'options' => ['class' => 'dropdown' ],
     'items' => $view_cat($data,$data[$parent_id][$i]['id'])];

    //рекурси€ - провер€ем нет ли дочерних категорий

   }
  return $result;
  };

  $result = $view_cat($arr_cat);
  return $result;
}


$list = getStructure();
           /*
NavBar::begin([
    'brandLabel' => 'Yii Navbar',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-default'
    ]
]);
*/

//echo Nav::widget(['options' => ['id' => 'topnav','class' => 'nav-pills'], 'items' => $list]);
//NavBar::end();

		?>

    </div>
</div>