<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basket".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $session_id
 * @property integer $last_update
 * @property integer $delivery_id
 * @property integer $address_id
 * @property integer $payment_id
 * @property integer $status
 */
class Basket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'basket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'session_id', 'last_update', 'delivery_id', 'address_id', 'payment_id', 'status'], 'required'],
            [['user_id', 'last_update', 'delivery_id', 'address_id', 'payment_id', 'status'], 'integer'],
            [['session_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'last_update' => 'Last Update',
            'delivery_id' => 'Delivery ID',
            'address_id' => 'Address ID',
            'payment_id' => 'Payment ID',
            'status' => 'Status',
        ];
    }
}
