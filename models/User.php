<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

class User extends ActiveRecord implements IdentityInterface
{
    public $imageFile;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],

            [['username', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'email'],
            [['role'], 'integer'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }



    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }


    public function upload()
    {

        if ($this->imageFile) {
            $fileName = 'user_' . $this->id . '_' . time() . '.' . $this->imageFile->extension;

            $this->imageFile->saveAs(Yii::getAlias('@webroot/uploads/') . $fileName);

            $this->image = $fileName;
            return true;
        }
        return false;
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    public static function findByUsernameOrEmail($value)
    {
        return static::find()
            ->where(['username' => $value])
            ->orWhere(['email' => $value])
            ->one();
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // ВИПРАВЛЕННЯ: Використовуємо security для перевірки хешу
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}