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
//	public $id;
	public $role_description;
	public $role;
	public $role_name;
	public $password;

    public static function tableName(){  return 'users';    }

    public function rules()
    {
        return [
            [['id','phone'], 'unique', 'message' => 'Такой телефон уже зергистрирован'],
            [['id', 'name','phone', 'role_description','role'], 'required'],
            [['name', 'phone','role_description','role_name','role' ], 'string'],
            [['id','status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'role_description', 'role', 'password', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
			[['role_description', 'role', 'role_name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Пользователь',
//            'fullname' => 'ФИО',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone' => 'Phone',
			'password' => 'Password',
            'status' => 'Status',
		    'role_description' => 'Role', 
			'role_name' => 'Rolename',
			'role' => 'Роль',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }  

	public function afterSave($insert, $model)
	{
        $user = User::findOne(['id' => $this->id]);
		$user->setPassword($this->password);
		$user->save();

//var_dump($this);die();

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->role);
        $auth->assign($role, $this->id);

		parent::afterSave($insert, $model);

	 	return true;
	}

    public function getAuth_Assignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
//        return $this->hasOne('auth_assignment', ['user_id' => 'id']);
    }

    public function getAuth_item()
    {
//        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
        return $this->hasOne(AuthItem::className(), ['description' => 'role_description']);
//        return $this->hasMany(AuthItem::className(), ['description' => 'role_description']);
//        return $this->hasOne('auth_assignment', ['user_id' => 'id']);
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
