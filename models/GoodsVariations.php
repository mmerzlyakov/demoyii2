<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_variations".
 *
 * @property integer $id
 * @property integer $good_id
 * @property string $code
 * @property string $full_name
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $comission
 * @property integer $status
 */
class GoodsVariations extends \yii\db\ActiveRecord
{
    public $producer_name;
    public $price_out;
    public $date_create;
    public $confirm;
    public $count;
    public $tags_name;

    public $productId;
    public $variantId;
    public $productPrice;
    public $productCommission;
    public $productDiscount;
    public $countPack;
    public $commissionId;
    public $categoryId;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_variations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'status','count'], 'integer'],
            [['description'], 'string'],
            [['price', 'comission'], 'number'],
            [['code', 'full_name', 'name'], 'string', 'max' => 128],
            [['producer_name'], 'string', 'max' => 128],
            [['price_out'], 'number'],
            [['confirm'], 'integer'],
            [['categoryId','productId','variantId','productPrice','productCommission','productDiscount','countPack','commissionId','date_create'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Good ID',
            'code' => 'Артикул',
            'full_name' => 'Поставщик',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'comission' => 'Комиссия',
            'status' => 'Активность',
            'confirm' => 'Модерация',
            'producer_name' => 'Производитель',
            'price_out' => 'Цена',
            'date_create' => 'Дата создания',
            'count' => 'Количество',
        ];
    }
/*
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'good_id']);
    }
*/
/*
    public function getProducer(){
        return $this->hasOne(Producers::className(), ['id' => 'producer_id'])
            ->viaTable('goods', ['id' => 'good_id']);
    }
*/
    public function getProducer(){
        return $this->hasOne(Producers::className(), ['id' => 'producer_id'])
            ->viaTable('goods', ['id' => 'good_id']);
    }

}
