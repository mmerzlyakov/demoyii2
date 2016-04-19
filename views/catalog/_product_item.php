<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;


print '<pre>';
print_r($this->params['breadcrumbs']);
print '</pre>';

$priceAttributes = [
    'commissionId' => $model->commissionId,
    'productPrice' => $model->productPrice,
    'countPack' => $model->countPack,
    'productCommission' => $model->productCommission,
    'productDiscount' => $model->productDiscount,
];

?>
    <div class="container">
        <div style="display: inline-block;width: 30%;">
            <a href="<?=$breadcrumbsUrl . '/' . $model->productId?>">
                <?=$model->name;?>
            </a>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?=\app\models\Goods::getPrice($model->productId,$model->variantId,0,$priceAttributes);?>

        </div>
        <div style="display: inline-block;width: 10%;">
            <?=\app\models\Goods::getPrice($model->productId,$model->variantId,5,$priceAttributes);?>
        </div>
        <div style="display: inline-block;width: 5%;">
            <?=$model->productPrice;?>
        </div>
        <div style="display: inline-block;width: 5%;">
            <?=$model->productCommission;?>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?=$image;?>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?=(implode('/',$sticker));?>
        </div>
    </div>


<?php
/*
<article class="item" data-key="<?= $model->id; ?>">
    <h2 class="title">
        <?= Html::a(
            Html::encode($model->name),
            Url::toRoute(['catalog/'.$model->id]), ['title' => $model->name]
        ) ?>
    </h2>
</article>
*/?>