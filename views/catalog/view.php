<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/*
print 'qq<pre>';
print_r(Yii::$app->controller->catalogMenu);
print '</pre>';
*/
/*
print '<pre>';
print_r($children);
print '</pre>';
print '<pre>';
print_r($parent);
print '</pre>';*/
/*
print '<pre>';
print_r($products);
print '</pre>';
*/
$this->title = $model->title;
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

?>
<div class="catalog-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    /*$this->render('_search', ['model' => $searchModelProducts]); */
    ?>
    <div class="container">
        <div style="display: inline-block;width: 30%;">
            Name
        </div>
        <div style="display: inline-block;width: 10%;">
            Price

        </div>
        <div style="display: inline-block;width: 10%;">
            Price with discount 5%
        </div>
        <div style="display: inline-block;width: 5%;">
            Original price
        </div>
        <div style="display: inline-block;width: 5%;">
            Commission
        </div>
        <div style="display: inline-block;width: 10%;">
            Image id
        </div>
        <div style="display: inline-block;width: 10%;">
            Stickers
        </div>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProviderProducts,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{pager}\n{sorter}\n{items}\n{summary}\n{pager}",
        'itemView' => function ($model, $key, $index, $widget) use (& $variationsAllProductsList,& $imagesAllProductsList,& $stickersAllProductsList,& $breadcrumbsUrl,&$urlList) {
            return $this->render('_product_item',[
                'model' => $model,
                'variation' => $variationsAllProductsList[$model->productId],
                'image' => $imagesAllProductsList[$model->productId],
                'sticker' => $stickersAllProductsList[$model->productId],
                'breadcrumbsUrl' => $breadcrumbsUrl,
                'url' => $urlList[$model->productId],
            ]);
        },

    ]); ?>

</div>
