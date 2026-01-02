<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\Post[] $posts */
/** @var yii\data\Pagination $pages */

$this->title = 'Мій IT Блог';
?>

<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-md-9">
                <?php foreach ($posts as $post): ?>
                    <div class="post-item" style="margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
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
                    <div class="card-header">Про блог</div>
                    <div class="card-body">
                        Курсова робота на тему Веб-розробки. Тут ми публікуємо новини про Yii2 та PHP.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>