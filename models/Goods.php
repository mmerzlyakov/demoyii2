<?php

namespace app\models;

use Yii;
use himiklab\yii2\search\behaviors\SearchBehavior;
use yii\db\Query;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $shop_id
 * @property integer $producer_id
 * @property integer $country_id
 * @property integer $weight_id
 * @property string $code
 * @property string $full_name
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $comission
 * @property integer $bonus
 * @property integer $order
 * @property integer $delay
 * @property integer $count_pack
 * @property integer $count_min
 * @property integer $rating
 * @property integer $discount
 * @property integer $main
 * @property integer $new
 * @property integer $sale
 * @property string $link
 * @property string $date
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $user_id
 * @property integer $user_last_update
 * @property string $date_create
 * @property string $date_update
 * @property integer $position
 * @property integer $s
 * @property integer $confirm
 * @property integer $status
 */
class Goods extends \yii\db\ActiveRecord
{
    public $producer_name;
    public $price_out;
    public $productImage;
/*
    public function behaviors(){
        return [
            'search' => [
                'class' => SearchBehavior::className(),
                'searchScope' => function ($model) {
                    // @var \yii\db\ActiveQuery $model
                    $model->select(['name', 'code', 'id', 'full_name']);
                    //$model->andWhere(['indexed' => true]);
                },
                'searchFields' => function ($model) {
                    // @var self $model
                    return [
                        ['name' => 'name', 'value' => $model->name],
                        ['name' => 'full_name', 'value' => strip_tags($model->full_name)],
                        ['name' => 'url', 'value' => '/goods/'.$model->id.'-'.$this->code, 'type' => SearchBehavior::FIELD_KEYWORD],
                        // ['name' => 'model', 'value' => 'page', 'type' => SearchBehavior::FIELD_UNSTORED],
                    ];
                }
            ],
        ];
    }
*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['show','type_id', 'shop_id', 'producer_id', 'country_id', 'weight_id', 'bonus', 'order', 'delay', 'count_pack', 'count_min', 'rating', 'discount', 'main', 'new', 'sale', 'user_id', 'user_last_update', 'position', 's', 'confirm', 'status'], 'integer'],
            [['description'], 'string'],
            [['price', 'comission'], 'number'],
            [['code', 'full_name', 'name', 'link', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 128],
            [['producer_name'], 'string', 'max' => 128],
            [['price_out'], 'number'],
            [['productImage','date', 'date_create', 'date_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип',
            'shop_id' => 'Shop ID',
            'producer_id' => 'Производитель',
            'country_id' => 'Страна',
            'weight_id' => 'Вес',
            'code' => 'Артикул',
            'full_name' => 'Название поставщика',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'comission' => 'Комиссия',
            'bonus' => 'Бонус',
            'order' => 'Заказ',
            'delay' => 'Задержка',
            'count_pack' => 'Количество в упаковке',
            'count_min' => 'Минимальное количество',
            'rating' => 'Рейтинг',
            'discount' => 'Скидка',
            'main' => 'На шлавной',
            'new' => 'Новинка',
            'sale' => 'Акция',
            'link' => 'Ссылка',
            'date' => 'Дата',
            'seo_title' => 'Seo Title',
            'seo_description' => 'Seo Description',
            'seo_keywords' => 'Seo Keywords',
            'user_id' => 'User ID',
            'user_last_update' => 'User Last Update',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата обновления',
            'position' => 'Сортировка',
            's' => 'S',
            'confirm' => 'Одобрен',
            'status' => 'Статус',
            'show' => 'Логическое удаление',
            //'producer_name' => 'Producer Name',
        ];
    }

    public function getproducer()
    {
        return $this->hasOne(Producers::className(), ['id' => 'producer_id']);
    }

    public function getgoods_variations()
    {
        return $this->hasMany(GoodsVariations::className(), ['good_id' => 'id']);
    }

    // Обработка цены;
    public static function getGoodPrice($goodId, $variationId = false, $discount = 0) {
        $product = (new Query())->from('goods')
            ->select([
                'goods.id',
                'goods.count_pack',
                'goods.discount',
                'shops.comission_id',
            ])
            ->leftJoin('shops','shops.id = goods.shop_id')
            ->where(['goods.id' => $goodId])
            ->one();

        // Загрузка данных товара;
        if ($product) {
            // Загрузка варианта товара;
            $variation = (new Query())->from('goods_variations')
                ->select([
                    'price',
                    'comission',
                ])
                ->where(['good_id' => $goodId])
                ->andWhere(['status' => 1]);

            if(isset($variationId) && !empty($variationId)){
                $variation->andWhere(['id' => $variationId]);
            }
            $variation = $variation->one();

            if ($variation) {
                // Проверка комиссии и рассчет цены;
                if ($product['comission_id'] == 1001){
                    $variation['price'] = ceil($variation['price'] * $product['count_pack']);
                }
                if ($product['comission_id'] == 1002){
                    $variation['price'] = ceil(($variation['price'] + $variation['price'] * $variation['comission'] / 100) * $product['count_pack']);
                }
                // Рассчет цены со скидкой;
                if ($product['discount']){
                    floor($variation['price'] = $variation['price'] * (100 - $discount) / 100);
                }

                return $variation['price'];
            }
        }
        return false;
    }

// Обработка комиссии;
    public static function getGoodComission($good_id, $variation_id = false, $discount = 0) {
        // Загрузка данных товара;
        $good = (new Query())->from('goods')
            ->select([
                'goods.id',
                'goods.shop_id',
                'goods.count_min',
                'goods.count_pack',
                'shops.comission_id',
            ])
            ->leftJoin('shops','shops.id = goods.shop_id')
            ->where(['goods.id' => $good_id])
            ->one();

        if (isset($good) && !empty($good)) {
            // Загрузка варианта товара;

            // Загрузка варианта товара;
            $variation = (new Query())->from('goods_variations')
                ->select([
                    'price',
                    'comission',
                ])
                ->where(['good_id' => $good_id])
                ->andWhere(['status' => 1]);

            if(isset($variation_id) && !empty($variation_id)){
                $variation->andWhere(['id' => $variation_id]);
            }
            $variation = $variation->one();

            if ($variation) {
                // Обновление цены и комиссии;
                $good['price'] = $variation['price'];
                $good['comission'] = $variation['comission'];
            }
            // Проверка комиссии и рассчет цены;
            if ($good['comission_id'] == 1001) $good['comission'] = round(ceil($good['price'] * $good['count_pack']) - ($good['price'] * $good['count_pack'] * (1 - $good['comission'] / 100)), 2);
            if ($good['comission_id'] == 1002) $good['comission'] = round(ceil(($good['price'] + $good['price'] * $good['comission'] / 100) * $good['count_pack']) - ($good['price'] * $good['count_pack']) - $discount, 2);
            // Вывод цены;
            return $good['comission'];
        }
        return false;
    }

    public static function getPrice($id, $variantId = false, $discount = 0,$data = []){
        $price = false;
        if(empty($data)){
            $data = (new Query())
                ->from('goods_variations')
                ->select([
                    'goods_variations.price as productPrice',
                    'goods_variations.comission as productCommission',
                    'goods_variations.id as variantId',

                    'goods.discount as productDiscount',
                    'goods.count_pack as countPack',
                    'shops.comission_id as commissionId',
                ])
                ->leftJoin('goods','goods.id = goods_variations.good_id')
                ->leftJoin('shops','goods.shop_id = shops.id')
                ->where([
                    'goods_variations.status' => 1,
                    'goods_variations.good_id' => $id,
                ]);

            if(!$variantId){
                $data->limit(1);
            }else{
                $data->andWhere(['goods_variations.id' => $variantId]);
            }
            $data = $data->one();
        }

        // Проверка комиссии и рассчет цены;
        if ($data['commissionId'] == 1001){
            $data['productPrice'] = ceil($data['productPrice'] * $data['countPack']);
        }
        if ($data['commissionId'] == 1002){
            $data['productPrice'] = ceil(($data['productPrice'] + $data['productPrice'] * $data['productCommission'] / 100) * $data['countPack']);
        }
        // Рассчет цены со скидкой;
        if ($data['productDiscount']){
            $data['productPrice'] = ceil($data['productPrice'] * (100 - $discount) / 100);
            //floor($data['productPrice'] = $data['productPrice'] * (100 - $discount) / 100);
        }

        return $data['productPrice'];
    }

    // Обработка комиссии
    public static function getProductCommission($id, $variantId = false, $discount = 0,$data =[]) {
        if(empty($data)){
            $data = (new Query())
                ->from('goods_variations')
                ->select([
                    'goods_variations.price as productPrice',
                    'goods_variations.comission as productCommission',
                    'goods_variations.id as variantId',

                    'goods.discount as productDiscount',
                    'goods.count_pack as countPack',
                    'shops.comission_id as commissionId',
                ])
                ->leftJoin('goods','goods.id = goods_variations.good_id')
                ->leftJoin('shops','goods.shop_id = shops.id')
                ->where([
                    'goods_variations.status' => 1,
                    'goods_variations.good_id' => $id,
                ]);

            if(!$variantId){
                $data->limit(1);
            }else{
                $data->andWhere(['goods_variations.id' => $variantId]);
            }
            $data = $data->one();
        }

        // Проверка комиссии и рассчет цены;
        if ($data['commissionId'] == 1001){
            $data['commission'] = round(ceil($data['productPrice'] * $data['countPack']) - ($data['productPrice'] * $data['countPack'] * (1 - $data['commission'] / 100)), 2);
        }
        if ($data['commissionId'] == 1002){
            $data['commission'] = round(ceil(($data['productPrice'] + $data['productPrice'] * $data['commission'] / 100) * $data['countPack']) - ($data['productPrice'] * $data['countPack']) - $discount, 2);
        }
        // Вывод цены;
        return $data['commission'];
    }

    public function findProductImage($id){
        $productImage = GoodsImages::find()->where(['good_id' => $id,'status' => 1])->orderBy('position')->one();
        if(!$productImage){

        }else{
            return $productImage->id;
        }
        return false;
    }

    public static function findProductImages($ids){
        $result = [];
        $productImages = GoodsImages::find()->where(['IN','good_id',$ids])->andWhere(['status' => 1])->groupBy('good_id')->orderBy('position')->all();
        if(!$productImages){

        }else{
            foreach($productImages as $image){
                $result[$image->good_id] = $image->id;
            }
            return $result;
        }
        return false;
    }

    // Обработка наклеек;
    function findProductStickers($ids,$data = false) {
        $result = [];
        $products = Goods::find()->where(['IN','id',$ids])->all();

        if(!$products){

        }else{
            foreach($products as $product){
                $result[$product->id] = [];
                if(isset($product->discount) && !empty($product->discount)){
                    $result[$product->id]['discount'] = true; // Добавление наклейки (акция);
                }
                // Проверка даты добавления товара (не позднее двух недель назад);
                if (strtotime($product->date_create) > (time() - 86400 * 14)) {
                    $result[$product->id]['news'] = true; // Добавление наклейки (новинка);
                }
                // Проверка товара на продажу за бонусы;
                if(isset($product->bonus) && !empty($product->bonus)){
                    $result['bonus'] = true; // Добавление наклейки (бонусы);
                }
            }
            return $result;
        }

        return false;
    }
/*
    // Загрузка опция вариантов цвет 1027;
    function good_variations_color($good_id) {
        global $db;
        $tags = array();
        // Загрузка групп вариантов;
        $sql = "SELECT `tags_groups`.`id`, `tags_groups`.`name`, `tags_links`.`variation_id` FROM `goods_variations` LEFT JOIN `tags_links` ON `tags_links`.`variation_id` = `goods_variations`.`id` LEFT JOIN `tags` ON `tags`.`id` = `tags_links`.`tag_id` LEFT JOIN `tags_groups` ON `tags_groups`.`id` = `tags`.`group_id` WHERE  `goods_variations`.`good_id` = '".$good_id."' AND `goods_variations`.`status` = '1' AND `tags`.`status` = '1' AND `tags_links`.`status` = '1' AND `tags_groups`.`type` = '1' AND `tags_groups`.`status` = '1' GROUP BY `tags_groups`.`id` ORDER BY `tags_groups`.`position` ASC";
        if ($tags = $db->all($sql)) {
            foreach ($tags as $i=>$tag_group) {
                // Загрузка вариантов;
                $sql = "SELECT `tags`.`id`, `tags`.`value` FROM `goods_variations` LEFT JOIN `tags_links` ON `tags_links`.`variation_id` = `goods_variations`.`id` LEFT JOIN `tags` ON `tags`.`id` = `tags_links`.`tag_id` LEFT JOIN `tags_groups` ON `tags_groups`.`id` = `tags`.`group_id`  WHERE `goods_variations`.`good_id` = '".$good_id."' AND `tags_groups`.`id` = '".$tag_group['id']."' AND `goods_variations`.`status` = '1' AND `tags`.`status` = '1' AND `tags_links`.`status` = '1' AND `tags_groups`.`type` = '1' AND `tags_groups`.`status` = '1' GROUP BY `tags`.`value` ORDER BY `tags`.`value` ASC";
                $tags[$i]['values'] = $db->all($sql);
                foreach($tags[$i]['values'] as $k =>$v) {
                    // Загрузка цвет;
                    $sql = "SELECT `color` FROM `tags_colors` WHERE `tag_id` = '" . $v['id'] . "' AND `status` = '1' LIMIT 1";
                    $tags[$i]['values'][$k]['color'] = $db->one($sql);
                    if(empty($tags[$i]['values'][$k]['color'])) unset($tags[$i]['values'][$k]['color']);
                }
            }
        }
        return $tags;
    }*/
}
