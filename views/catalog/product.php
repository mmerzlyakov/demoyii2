<?php

use yii\helpers\Html;

$this->title = $model->name;
$url = '/' . Yii::$app->params['catalogPath'] . '/';
$breadcrumbsUrl = '';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => $url];
foreach($breadcrumbsCatalog as $item){
    if($item['title'] != $this->title){
        $url .= $item['alias'] . '/';
        $this->params['breadcrumbs'][] = ['label' => $item['title'], 'url' => $url];
    }else{
        $url .= $item['alias'] . '/';
        $breadcrumbsUrl = $url;
    }
}
$this->params['breadcrumbs'][] = $this->title;

print '<pre>';
print_r($model);
print '</pre>';

print '<pre>';
print_r($variations);
print '</pre>';

print '<pre>';
print_r($tags);
print '</pre>';


print '<pre>';
print_r($productImages);
print '</pre>';

