<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $fullname // non active
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property integer $status
 * @property integer $role_description
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property AuthAssignment $authAssignment
 */

class UserAdmin extends \yii\db\ActiveRecord
{      	
	public $role_description;
	public $role;
	public $role_name;
	public $password;

    public static function tableName(){  return 'users';    }

    public function rules()
    {
        return [
            [['id','phone'], 'unique', 'message' => 'Такой телефон уже зергистрирован'],
            [['id', 'name','phone', 'role'], 'required'],
            [['name', 'phone','role_description','role_name','role' ], 'string'],
            [['id','status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'role_description', 'role', 'password', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'],'email', 'message' => 'Введите адрес электронной почты правильно!'],
			[['role_description', 'role', 'role_name', 'email', 'password'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя пользователя',
            'auth_key' => 'Ключ авторизации',
            'password_hash' => 'Пароль хеш',
            'password_reset_token' => 'Запрос сброса пароля',
            'email' => 'E-mail',
            'phone' => 'Телефон',
			'password' => 'Пароль',
            'status' => 'Статус пользователя',
		    'role_description' => 'Role', 
			'role_name' => 'Rolename',
			'role' => 'Роль',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    public static function findById($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public function getAuth_Assignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getAuth_item()
    {
    // Вариантики
//        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
//        return $this->hasMany(AuthItem::className(), ['description' => 'role_description']);
        return $this->hasOne(AuthItem::className(), ['description' => 'role_description']);
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
