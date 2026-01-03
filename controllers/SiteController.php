<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Post;
use yii\data\Pagination;
use app\models\Category;
use app\models\SignupForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($category_id = null, $search = null, $tag = null)
    {
        $query = Post::find()->where(['status' => 1]);

        if ($category_id) {
            $query->andWhere(['category_id' => $category_id]);
        }

        if ($search) {
            $query->andWhere(['or',
                ['like', 'title', $search],
                ['like', 'description', $search]
            ]);
        }

        if ($tag) {
            $query->joinWith('tags')->andWhere(['tag.title' => $tag]);
        }

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3]);

        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['post.id' => SORT_DESC])
            ->all();

        $categories = Category::find()->all();

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
            'categories' => $categories,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * User Profile Page
     */
    public function actionProfile()
    {
        // Якщо гість - відправляємо на вхід
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        // Отримуємо поточного користувача
        $user = Yii::$app->user->identity;

        return $this->render('profile', [
            'model' => $user,
        ]);
    }
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    public function actionProfileEdit()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::$app->user->identity;

        if ($model->load(Yii::$app->request->post())) {


            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');


            if ($model->validate()) {

                if ($model->imageFile) {
                    $model->upload();
                }


                $model->save(false);

                Yii::$app->session->setFlash('success', 'Профіль успішно оновлено!');
                return $this->redirect(['site/profile']);
            }
        }

        return $this->render('profile-edit', [
            'model' => $model,
        ]);
    }
    public function actionAbout()
    {
        return $this->render('about');
    }
}