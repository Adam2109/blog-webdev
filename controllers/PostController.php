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
                    'only' => ['create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Post models.
     *
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
            $commentForm->status = 1;

            if ($commentForm->save()) {
                Yii::$app->session->setFlash('success', 'Коментар додано!');
                return $this->refresh();
            }
        }

        $model->updateCounters(['viewed' => 1]);

        return $this->render('view', [
            'model' => $model,
            'commentForm' => $commentForm,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
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
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id !== Yii::$app->user->id) {
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
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        if ($model->user_id !== Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException('Ви не маєте прав видаляти цю статтю.');
        }
            $model->delete();

            return $this->redirect(['index']);
    }


    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
