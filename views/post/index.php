<?php

use app\models\Post;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Category;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\PostSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Управління статтями';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Створити статтю', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],


                    [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 50px;'],
                    ],


                    [
                            'label' => 'Фото',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->image
                                        ? Html::img('@web/uploads/' . $model->image, ['style' => 'width: 50px; height: 50px; object-fit: cover; border-radius: 4px;'])
                                        : '<span class="text-muted">Без фото</span>';
                            },
                    ],

                    'title',


                    [
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                return $model->category ? $model->category->title : 'Без категорії';
                            },
                            'filter' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
                    ],


                    [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'filter' => [
                                    1 => 'Опубліковано',
                                    0 => 'Чернетка',
                            ],
                            'value' => function ($model) {
                                if ($model->status == 1) {
                                    return '<span class="badge bg-success">Опубліковано</span>';
                                } else {
                                    return '<span class="badge bg-warning text-dark">Чернетка</span>';
                                }
                            },
                            'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    ],


                    [
                            'attribute' => 'user_id',
                            'value' => function ($model) {
                                return $model->user ? $model->user->username : 'Невідомо';
                            },
                    ],

                    'date',


                    [
                            'attribute' => 'viewed',
                            'contentOptions' => ['style' => 'text-align: center;'],
                    ],

                    [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Post $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                    ],
            ],
    ]); ?>

</div>