<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Будь ласка, введіть ім\'я користувача.'],

            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Це ім\'я користувача вже зайняте.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Будь ласка, введіть Email.'],
            ['email', 'email', 'message' => 'Некоректна електронна адреса.'],
            ['email', 'string', 'max' => 255],

            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ця електронна адреса вже зареєстрована.'],

            ['password', 'required', 'message' => 'Введіть пароль.'],
            ['password', 'string', 'min' => 6, 'message' => 'Пароль має містити мінімум 6 символів.'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString();

        return $user->save() ? $user : null;
    }
}