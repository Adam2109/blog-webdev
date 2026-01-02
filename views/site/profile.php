<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Мій профіль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                         alt="User Avatar"
                         class="rounded-circle img-fluid mb-3"
                         style="width: 150px;">

                    <h3 class="card-title"><?= Html::encode($model->username) ?></h3>

                    <p class="text-muted">
                        <?= $model->isAdmin() ? '<span class="badge bg-danger">Адміністратор</span>' : 'Користувач' ?>
                    </p>

                    <hr>
                    <div class="d-grid gap-2">
                        <?= Html::a('Вийти', ['site/logout'], [
                                'class' => 'btn btn-outline-danger',
                                'data-method' => 'post'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">

            <?php if ($model->isAdmin()): ?>
                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-pencil-square"></i> Мої статті (<?= count($model->posts) ?>)</span>
                        <a href="<?= Url::to(['post/create']) ?>" class="btn btn-sm btn-light text-primary fw-bold">
                            + Створити
                        </a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($model->posts)): ?>
                            <?php foreach ($model->posts as $post): ?>
                                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?= Html::encode($post->title) ?>
                                    <span class="badge bg-secondary rounded-pill" title="Переглядів">
                                        <i class="bi bi-eye"></i> <?= $post->viewed ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-3 text-muted text-center">Ви ще не написали жодної статті.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>


            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-chat-dots"></i> Мої останні коментарі
                </div>
                <div class="card-body">
                    <?php if (!empty($model->comments)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_reverse($model->comments) as $comment): ?>
                                <div class="list-group-item ps-0 pe-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            До статті:
                                            <a href="<?= Url::to(['post/view', 'id' => $comment->post_id]) ?>">
                                                <?= Html::encode($comment->post ? $comment->post->title : 'Видалена стаття') ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted"><?= Yii::$app->formatter->asDate($comment->date) ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small fst-italic">
                                        "<?= \yii\helpers\StringHelper::truncate(Html::encode($comment->text), 100) ?>"
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center my-4">Ви ще не залишили жодного коментаря.</p>
                        <div class="text-center">
                            <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary btn-sm">Читати статті</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>