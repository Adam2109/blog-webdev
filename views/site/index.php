<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\Post[] $posts */
/** @var yii\data\Pagination $pages */
/** @var app\models\Category[] $categories */

$this->title = 'IT Блог - Головна';
?>
<div class="site-index">

    <div class="row">

        <div class="col-md-8">
            <h1 class="mb-4">Останні публікації</h1>

            <div class="d-flex justify-content-end mb-3">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-sort-down"></i> Сортувати:
                        <b>
                            <?php
                            // ВИПРАВЛЕННЯ: Використовуємо масив замість match для сумісності зі старим PHP
                            $sortLabels = [
                                    'popular' => 'Популярні',
                                    'old' => 'Старіші',
                                    'new' => 'Новіші',
                            ];
                            // Виводимо текст або 'Новіші' за замовчуванням
                            echo $sortLabels[$sort ?? 'new'] ?? 'Новіші';
                            ?>
                        </b>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                        <li>
                            <a class="dropdown-item <?= ($sort ?? 'new') == 'new' ? 'active' : '' ?>"
                               href="<?= \yii\helpers\Url::current(['sort' => 'new']) ?>">
                                Спочатку новіші
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= ($sort ?? '') == 'old' ? 'active' : '' ?>"
                               href="<?= \yii\helpers\Url::current(['sort' => 'old']) ?>">
                                Спочатку старіші
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= ($sort ?? '') == 'popular' ? 'active' : '' ?>"
                               href="<?= \yii\helpers\Url::current(['sort' => 'popular']) ?>">
                                Популярні
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>

            <?php if (!empty($posts)): ?>

                <?php foreach ($posts as $post): ?>
                    <article class="card mb-4 shadow-sm">
                        <?php if ($post->image): ?>
                            <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>">
                                <img src="<?= Yii::getAlias('@web/uploads/') . $post->image ?>"
                                     class="card-img-top"
                                     alt="<?= Html::encode($post->title) ?>"
                                     style="height: 300px; object-fit: cover;">
                            </a>
                        <?php endif; ?>

                        <div class="card-body">
                            <h2 class="card-title">
                                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>" class="text-decoration-none text-dark">
                                    <?= Html::encode($post->title) ?>
                                </a>
                            </h2>

                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar"></i> <?= Yii::$app->formatter->asDate($post->date) ?> |
                                <i class="bi bi-folder"></i> <?= $post->category ? $post->category->title : 'Без категорії' ?> |
                                <i class="bi bi-eye"></i> <?= $post->viewed ?> |
                                <i class="bi bi-chat-dots"></i> <?= count($post->comments) ?>
                            </p>

                            <p class="card-text">
                                <?= \yii\helpers\StringHelper::truncate(strip_tags($post->description), 200) ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>" class="btn btn-outline-primary">
                                    Читати далі &rarr;
                                </a>

                                <?php if($post->tags): ?>
                                    <div>
                                        <?php foreach(array_slice($post->tags, 0, 3) as $tag): ?>
                                            <a href="<?= Url::to(['site/index', 'tag' => $tag->title]) ?>" class="badge bg-secondary text-decoration-none">
                                                <?= Html::encode($tag->title) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>

                <div class="d-flex justify-content-center mt-4">
                    <?= LinkPager::widget([
                            'pagination' => $pages,
                            'options' => ['class' => 'pagination justify-content-center'],
                            'linkOptions' => ['class' => 'page-link'],
                            'pageCssClass' => 'page-item',
                            'disabledPageCssClass' => 'page-item disabled',
                            'prevPageCssClass' => 'page-item',
                            'nextPageCssClass' => 'page-item',
                            'activePageCssClass' => 'active',
                    ]); ?>
                </div>

            <?php else: ?>
                <div class="alert alert-info">
                    За вашим запитом статей не знайдено.
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">Пошук</div>
                <div class="card-body">
                    <form method="get" action="<?= Url::to(['site/index']) ?>" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Введіть слово..." value="<?= Yii::$app->request->get('search') ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">Категорії</div>
                <div class="list-group list-group-flush">
                    <a href="<?= Url::to(['site/index']) ?>" class="list-group-item list-group-item-action <?= !Yii::$app->request->get('category_id') ? 'active' : '' ?>">
                        Всі категорії
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= Url::to(['site/index', 'category_id' => $category->id]) ?>"
                           class="list-group-item list-group-item-action <?= Yii::$app->request->get('category_id') == $category->id ? 'active' : '' ?>">
                            <?= Html::encode($category->title) ?>
                            <span class="badge bg-secondary float-end"><?= $category->getPosts()->count() ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
                <div class="d-grid">
                    <a href="<?= Url::to(['post/create']) ?>" class="btn btn-warning btn-lg shadow-sm">
                        <i class="bi bi-pencil-square"></i> Написати статтю
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>