<?php

namespace app\models;

use Yii;
use app\models\Comment;
use app\models\Like;
/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $content
 * @property string|null $date
 * @property string|null $image
 * @property int|null $viewed
 * @property int|null $user_id
 * @property int|null $status
 * @property int|null $category_id
 *
 * @property Category $category
 * @property Comment[] $comments
 * @property PostTag[] $postTags
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{

    public $imageFile;
    public $tagsInput;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'content', 'date', 'image', 'user_id', 'category_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['title'], 'required'],
            [['description', 'content'], 'string'],
            [['date'], 'safe'],
            [['viewed', 'user_id', 'status', 'category_id'], 'integer'],
            [['title', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['tagsInput'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[PostTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('post_tag', ['post_id' => 'id']);
    }


    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function getLikes()
    {
        return $this->hasMany(Like::class, ['post_id' => 'id']);
    }


    public function getLikesCount()
    {
        return $this->getLikes()->count();
    }

    public function isLikedByCurrentUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Like::find()
            ->where(['post_id' => $this->id, 'user_id' => Yii::$app->user->id])
            ->exists();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->tagsInput) {
            $this->unlinkAll('tags', true);
            $tagsArray = explode(',', $this->tagsInput);

            foreach ($tagsArray as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                $tag = Tag::findOne(['title' => $tagName]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->title = $tagName;
                    $tag->save();
                }

                $this->link('tags', $tag);
            }
        }
    }
}