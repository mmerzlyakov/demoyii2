<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Useradmin;

/**
*
*
* @property string $description
* @property string $role
*
*/

class UserSearch extends Useradmin
{
    public $description;
    public $role_description;
	public $role;
	public $role_name;

    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at',], 'integer'],
//            [['name', 'role_description', 'role', 'fullname', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone', 'description'], 'safe'],
            [['name', 'role_description', 'role', 'role_name', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone', 'description'], 'safe'],
  //          [['name', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone', 'description'], 'safe'],
        ];
    }

    public function relations()
    {
        return ['description' => array(self::HAS_ONE, 'id', 'user_id')]; 
//	    array('auth_item,'=>array(self::HAS_MANY, 'auth_item', 'name'),);
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Useradmin::find()
		->select([
		'id',
//		'fullname',
		'phone',
		'email',
		'users.name',
		'auth_Assignment.item_name as role',
		'auth_Assignment.description as description',
		'auth_item.name as role_name',
		'auth_item.description as role_description'
	
	])
	->leftjoin('auth_Assignment','auth_Assignment.user_id = users.id')
	->leftjoin('auth_item','auth_item.name = auth_Assignment.item_name')
//	->where('id = user.id')
//	->orderby('id')
	->asarray();

//Проверяем поиск
//   	->one();
//var_dump($query);
//die();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->orFilterWhere([
            'id' => $this->id,
//            'username' => $this->username,
//            'fullname' => $this->fullname,
			'auth_assignment.item_name' => $this->role,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
		    'auth_assignment.description' => $this->description,
		    'auth_item.name' => $this->role_name,
        ]);          
                      
        $query->andFilterWhere(['like', 'users.name', $this->name])
//            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'auth_item.description', $this->description])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'auth_assignment.item_name', $this->role])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
			->all();

        return $dataProvider;
    }
}
