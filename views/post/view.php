<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Post $model */
/** @var app\models\Comment $commentForm */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Головна', 'url' => ['site/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->image): ?>
        <div class="mb-4 text-center">
            <img src="<?= Yii::getAlias('@web/uploads/') . $model->image ?>"
                 class="img-fluid rounded"
                 alt="<?= Html::encode($model->title) ?>"
                 style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover;">
        </div>
    <?php endif; ?>

    <p class="text-muted">
        <small>
            Дата: <?= Yii::$app->formatter->asDate($model->date, 'long') ?> |
            Категорія: <b><?= $model->category ? $model->category->title : 'Без категорії' ?></b> |
            Переглядів: <b><?= $model->viewed ?></b>
        </small>
    </p>

    <?php if (!empty($model->tags)): ?>
        <div class="mb-3">
            <strong>Теги:</strong>
            <?php foreach ($model->tags as $tag): ?>
                <a href="<?= Url::to(['site/index', 'tag' => $tag->title]) ?>" class="badge bg-secondary text-decoration-none">
                    <?= Html::encode($tag->title) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                            'confirm' => 'Ви впевнені, що хочете видалити цю статтю?',
                            'method' => 'post',
                    ],
            ]) ?>
        </p>
        <hr>
    <?php endif; ?>

    <div class="post-content" style="font-size: 1.1em; line-height: 1.6; margin-bottom: 30px;">
        <?= nl2br(Html::encode($model->content)) ?>
    </div>

    <hr>

    <div class="social-share mt-4 mb-5">
        <h5>Поділитися:</h5>
        <a href="https://t.me/share/url?url=<?= Url::current([], true) ?>&text=<?= Html::encode($model->title) ?>"
           target="_blank" class="btn btn-primary btn-sm" style="background-color: #0088cc; border: none;">
            Telegram
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= Url::current([], true) ?>"
           target="_blank" class="btn btn-primary btn-sm" style="background-color: #3b5998; border: none;">
            Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?= Html::encode($model->title) ?>&url=<?= Url::current([], true) ?>"
           target="_blank" class="btn btn-primary btn-sm" style="background-color: #1da1f2; border: none;">
            Twitter
        </a>
    </div>

    <div class="comments-section bg-light p-4 rounded">
        <h3>Коментарі (<?= count($model->comments) ?>)</h3>
        <hr>

        <?php if (!empty($model->comments)): ?>
            <?php foreach ($model->comments as $comment): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title text-primary m-0">
                                <?= Html::encode($comment->user ? $comment->user->username : 'Гість') ?>
                            </h5>
                            <small class="text-muted">
                                <?= Yii::$app->formatter->asDate($comment->date, 'medium') ?>
                            </small>
                        </div>
                        <p class="card-text">
                            <?= Html::encode($comment->text) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Поки немає коментарів. Будьте першим!</p>
        <?php endif; ?>

        <div class="mt-4">
            <h4>Залишити коментар</h4>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="comment-form">
                    <?php $form = ActiveForm::begin([
                            'action' => ['post/view', 'id' => $model->id], // Явно вказуємо куди слати форму
                    ]); ?>

                    <?= $form->field($commentForm, 'text')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Напишіть вашу думку...'
                    ])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Надіслати коментар', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Щоб залишити коментар, будь ласка
                    <a href="<?= Url::to(['site/login']) ?>" class="alert-link">увійдіть</a> або
                    <a href="<?= Url::to(['site/signup']) ?>" class="alert-link">зареєструйтеся</a>.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>