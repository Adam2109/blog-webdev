<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
/** @var yii\web\View $this */
/** @var app\models\Post[] $posts */
/** @var yii\data\Pagination $pages */
/** @var app\models\Category[] $categories */

$this->title = 'Мій IT Блог';
?>

<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-md-9">
                <?php foreach ($posts as $post): ?>
                    <div class="post-item" style="margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                        <?php if ($post->image): ?>
                            <div class="post-image" style="margin-bottom: 15px;">
                                <img src="<?= Yii::getAlias('@web/uploads/') . $post->image ?>"
                                     alt="<?= $post->title ?>"
                                     class="img-fluid"
                                     style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        <h2>
                            <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>">
                                <?= $post->title ?>
                            </a>
                        </h2>

                        <p class="text-muted">
                            <small>
                                Дата: <?= $post->date ?> |
                                Категорія: <?= $post->category ? $post->category->title : 'Без категорії' ?>
                            </small>
                        </p>

                        <p><?= $post->description ?></p>

                        <a class="btn btn-outline-primary" href="<?= Url::to(['post/view', 'id' => $post->id]) ?>">
                            Читати далі &raquo;
                        </a>
                    </div>
                <?php endforeach; ?>

                <div class="pagination-wrapper">
                    <?= LinkPager::widget(['pagination' => $pages]) ?>
                </div>
            </div>

            <div class="col-md-3">

                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">Категорії</div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <a href="<?= Url::to(['site/index']) ?>" class="text-decoration-none">
                                Всі статті
                            </a>
                        </li>

                        <?php foreach ($categories as $category): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= Url::to(['site/index', 'category_id' => $category->id]) ?>" class="text-decoration-none">
                                    <?= Html::encode($category->title) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Про автора</div>
                    <div class="card-body">
                        <p>Вітаю! Це мій блог, розроблений на Yii2 в рамках курсового проекту.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>