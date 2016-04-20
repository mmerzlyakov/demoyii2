<?php
// _list_item.php
//use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var object $model
 * @var object $variation
 * @var array $image
 * @var array $sticker
 * @var string $alias
 * @var string $url
 *
 */

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
            <a href="<?= Url::toRoute($url)?>">
                <?= $model->name;?>
            </a>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?= \app\models\Goods::getPrice($model->productId,$model->variantId,0,$priceAttributes);?>

        </div>
        <div style="display: inline-block;width: 10%;">
            <?= \app\models\Goods::getPrice($model->productId,$model->variantId,5,$priceAttributes);?>
        </div>
        <div style="display: inline-block;width: 5%;">
            <?= $model->productPrice;?>
        </div>
        <div style="display: inline-block;width: 5%;">
            <?= $model->productCommission;?>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?php
            if(is_array($image)){
                foreach($image as $item){
                    print $item;
                }
            }
            ?>
        </div>
        <div style="display: inline-block;width: 10%;">
            <?= (implode('/',$sticker));?>
        </div>
    </div>
