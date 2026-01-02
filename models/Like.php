<?php

namespace app\models;

use Yii;

class Like extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'post_like';
    }

    public function rules()
    {
        return [
            [['user_id', 'post_id'], 'required'],
            [['user_id', 'post_id'], 'integer'],
            [['user_id', 'post_id'], 'unique', 'targetAttribute' => ['user_id', 'post_id']],
        ];
    }
}