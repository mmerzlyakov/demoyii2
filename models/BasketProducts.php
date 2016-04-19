<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basket_products".
 *
 * @property integer $id
 * @property integer $basket_id
 * @property integer $product_id
 * @property integer $variant_id
 * @property integer $count
 */
class BasketProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'basket_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['basket_id', 'product_id', 'variant_id', 'count'], 'required'],
            [['basket_id', 'product_id', 'variant_id', 'count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'basket_id' => 'Basket ID',
            'product_id' => 'Product ID',
            'variant_id' => 'Variant ID',
            'count' => 'Count',
        ];
    }
}
