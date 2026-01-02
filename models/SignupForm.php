<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email; // <--- ДОДАЛИ
    public $password;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Цей логін вже зайнято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            // ДОДАЛИ ПРАВИЛА ДЛЯ EMAIL
            ['email', 'trim'],
            ['email', 'required'], // При реєстрації обов'язково
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ця пошта вже зареєстрована.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email; // <--- ЗБЕРІГАЄМО EMAIL
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString();

        return $user->save() ? $user : null;
    }
}