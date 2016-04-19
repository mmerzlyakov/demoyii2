<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_images".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $variation_id
 * @property string $hash
 * @property string $date
 * @property integer $position
 * @property integer $status
 */
class GoodsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'variation_id', 'position', 'status'], 'integer'],
            [['date'], 'safe'],
            [['hash'], 'string', 'max' => 32]
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
            'variation_id' => 'Variation ID',
            'hash' => 'Hash',
            'date' => 'Date',
            'position' => 'Position',
            'status' => 'Status',
        ];
    }
}
