<?php
namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $phone;
    public $role;
    public $isNewRecord = true;
    public $name;
    public $email;
    public $password;
	public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
  //          ['username', 'filter', 'filter' => 'trim'],
            ['phone', 'required', 'message' => 'Введите телефон!'],
            ['phone', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Такой номер телефона уже зарегистрирован'],
            ['phone', 'string', 'min' => 10, 'max' => 10, 'tooShort' => 'Номер телефона 10 цифр без пробелов и дефисов!'],

/*
* Родная валидация
*
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required',  'message' => 'Введите имя пользователя!'],
//            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
*/     

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required',  'message' => 'Введите имя пользователя!'],
//            ['name', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['name', 'string', 'min' => 2, 'max' => 255],
    

            ['email', 'filter', 'filter' => 'trim'],
//            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
      
            ['password', 'required',  'message' => 'Введите пароль!'],
            ['password', 'string', 'min' => 6, 'tooShort' => 'Пароль минимум 6 символов!'],
            ['verifyCode', 'captcha', 'message' => 'Введите верно проверочный код!'],
			[['phone', 'password'], 'validateEmpty', 'skipOnEmpty'=> false]
        ];
    }
                                            
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */

	public function validateEmpty()
	{
		if(empty($this->phone))
		{
			$errorMsg= 'Введите телефон!';
			$this->addError('phone',$errorMsg);
		} 
		if(empty($this->password))
		{
			$errorMsg= 'Введите пароль!';
			$this->addError('password',$errorMsg);
		}
	}

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->city_id = 1001;
        $user->store_id = 0;

        $user->setPassword($this->password);
        $user->generateAuthKey();

		if($signedUser = $user->save())
		{

//var_dump($user->id);
//die();
		//	return json_encode($signedUser);

			//Менеджер авторизации приложения(сам записывает/присваивает роль)
	        $auth = Yii::$app->authManager;
	        $role = $auth->getRole('user');
	        $auth->assign($role, $user->id);
			
			return $user;
		}

        return null;
    }
}
