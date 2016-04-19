<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shops".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $name_full
 * @property string $contract
 * @property string $tax_number
 * @property string $description
 * @property string $phone
 * @property string $min_order
 * @property string $delivery_delay
 * @property integer $delay
 * @property integer $comission_id
 * @property string $comission_value
 * @property integer $count
 * @property integer $show
 * @property integer $notice
 * @property string $registration
 * @property integer $status
 *
 * @property UsersRoles[] $usersRoles
 */
class Shops extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'delay', 'comission_id', 'count', 'show', 'notice', 'status'], 'integer'],
            [['description'], 'required'],
            [['description'], 'string'],
            [['min_order', 'comission_value'], 'number'],
            [['delivery_delay', 'registration'], 'safe'],
            [['name', 'phone'], 'string', 'max' => 64],
            [['name_full'], 'string', 'max' => 128],
            [['contract', 'tax_number'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'name' => 'Name',
            'name_full' => 'Name Full',
            'contract' => 'Contract',
            'tax_number' => 'Tax Number',
            'description' => 'Description',
            'phone' => 'Phone',
            'min_order' => 'Min Order',
            'delivery_delay' => 'Delivery Delay',
            'delay' => 'Delay',
            'comission_id' => 'Comission ID',
            'comission_value' => 'Comission Value',
            'count' => 'Count',
            'show' => 'Show',
            'notice' => 'Notice',
            'registration' => 'Registration',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersRoles()
    {
        return $this->hasMany(UsersRoles::className(), ['shop_id' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasOne(UserShop::className(), ['id' => 'user_id']);
    }
}
