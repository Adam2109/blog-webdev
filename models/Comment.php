<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $text
 * @property int|null $user_id
 * @property int|null $post_id
 * @property int|null $parent_id
 * @property int|null $status
 * @property string|null $date
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'comment';
    }

    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['user_id', 'post_id', 'parent_id', 'status'], 'integer'],
            [['date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Коментар',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }


    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }


    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->date = date('Y-m-d');
            }
            return true;
        }
        return false;
    }
}