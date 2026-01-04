<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Comment;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\models\Like;
use yii\web\Response;
use yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['create', 'update', 'delete', 'delete-comment'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete', 'delete-comment'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'delete-comment' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Post models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $commentForm = new Comment();

        if ($commentForm->load(Yii::$app->request->post())) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Щоб коментувати, увійдіть у систему.');
                return $this->refresh();
            }
            $commentForm->user_id = Yii::$app->user->id;
            $commentForm->post_id = $id;

            if ($commentForm->save()) {
                Yii::$app->session->setFlash('success', 'Коментар додано!');
                return $this->refresh();
            }
        }



        $currentTagIds = ArrayHelper::getColumn($model->tags, 'id');
        $relatedPosts = [];

        if (!empty($currentTagIds)) {
            $relatedPosts = Post::find()
                ->joinWith('tags')
                ->where(['in', 'tag.id', $currentTagIds])
                ->andWhere(['!=', 'post.id', $id])
                ->andWhere(['status' => 1])
                ->distinct()
                ->limit(3)
                ->all();
        }


        if (count($relatedPosts) < 3) {
            $needed = 3 - count($relatedPosts);


            $excludeIds = ArrayHelper::getColumn($relatedPosts, 'id');
            $excludeIds[] = $id;

            $morePosts = Post::find()
                ->where(['category_id' => $model->category_id])
                ->andWhere(['not in', 'id', $excludeIds])
                ->andWhere(['status' => 1])
                ->limit($needed)
                ->orderBy(['id' => SORT_DESC])
                ->all();


            $relatedPosts = array_merge($relatedPosts, $morePosts);
        }

        $model->updateCounters(['viewed' => 1]);

        return $this->render('view', [
            'model' => $model,
            'commentForm' => $commentForm,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Creates a new Post model.
     */
    public function actionCreate()
    {

        if (!Yii::$app->user->identity->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('Тільки адміністратор може створювати статті.');
        }

        $model = new Post();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->user_id = Yii::$app->user->id;
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

                if ($model->imageFile) {
                    $uid = md5(uniqid(rand(), true));
                    $fileName = $uid . '.' . $model->imageFile->extension;

                    $uploadPath = \Yii::getAlias('@webroot/uploads/');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $model->imageFile->saveAs($uploadPath . $fileName);
                    $model->image = $fileName;
                }

                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if (!Yii::$app->user->identity->isAdmin() && $model->user_id !== Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException('Ви не маєте прав редагувати цю статтю.');
        }

        $oldImage = $model->image;

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $uid = md5(uniqid(rand(), true));
                $fileName = $uid . '.' . $model->imageFile->extension;
                $model->imageFile->saveAs(Yii::getAlias('@webroot/uploads/') . $fileName);
                $model->image = $fileName;
            } else {
                $model->image = $oldImage;
            }

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->user->isGuest &&
            (Yii::$app->user->identity->isAdmin() || $model->user_id == Yii::$app->user->id)) {


            $model->delete();
            Yii::$app->session->setFlash('success', 'Статтю успішно видалено!');

        } else {
            throw new \yii\web\ForbiddenHttpException('Ви не маєте прав видаляти цю статтю.');
        }

        return $this->redirect(['site/index']);
    }

    /**
     * Лайки (AJAX)
     */
    public function actionLike($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Login required'];
        }

        $userId = Yii::$app->user->id;
        $like = Like::findOne(['post_id' => $id, 'user_id' => $userId]);

        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            $like = new Like();
            $like->post_id = $id;
            $like->user_id = $userId;
            $like->save();
            $isLiked = true;
        }

        $count = Like::find()->where(['post_id' => $id])->count();

        return [
            'success' => true,
            'likesCount' => $count,
            'isLiked' => $isLiked,
        ];
    }

    /**
     * Видалення коментаря
     */
    public function actionDeleteComment($id)
    {
        $comment = Comment::findOne($id);


        if ($comment && !Yii::$app->user->isGuest &&
            (Yii::$app->user->id == $comment->user_id || Yii::$app->user->identity->isAdmin())) {

            $comment->delete();
            Yii::$app->session->setFlash('success', 'Коментар видалено.');
        } else {
            Yii::$app->session->setFlash('error', 'Помилка видалення коментаря.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}