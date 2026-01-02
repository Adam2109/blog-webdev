<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Введіть логін.'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Цей логін вже зайнятий.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required', 'message' => 'Введіть пароль.'],
            ['password', 'string', 'min' => 6, 'message' => 'Пароль має бути не менше 6 символів.'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        // Генеруємо безпечний хеш пароля
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        // Генеруємо випадковий ключ авторизації
        $user->auth_key = Yii::$app->security->generateRandomString();

        return $user->save() ? $user : null;
    }
}