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
                 style="max-width: 100%; height: auto;">
        </div>
    <?php endif; ?>

    <p class="text-muted">
        <small>
            Дата: <?= Yii::$app->formatter->asDate($model->date, 'long') ?> |
            Категорія: <b><?= $model->category ? $model->category->title : 'Без категорії' ?></b>
        </small>
    </p>

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
    <?php endif; ?>

    <hr>

    <div class="post-content" style="font-size: 1.1em; line-height: 1.6;">
        <?php
        echo nl2br(Html::encode($model->content));
        ?>
    </div>

    <hr>

    <div class="social-share mt-4 mb-4">
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
    <hr>
    <div class="comments-section mt-5">

        <h3>Коментарі (<?= count($model->comments) ?>)</h3>

        <?php if (!empty($model->comments)): ?>
            <?php foreach ($model->comments as $comment): ?>
                <div class="card mb-3" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <strong>
                                <?= $comment->user ? Html::encode($comment->user->username) : 'Гість' ?>
                            </strong>
                            <span style="font-size: 0.8em; color: #888;">
                                (<?= Yii::$app->formatter->asDate($comment->date, 'medium') ?>)
                            </span>
                        </h6>
                        <p class="card-text" style="margin-top: 10px;">
                            <?= Html::encode($comment->text) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Поки немає коментарів. Будьте першим!</p>
        <?php endif; ?>

        <hr>

        <h4>Залишити коментар</h4>

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="comment-form">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($commentForm, 'text')->textarea(['rows' => 3, 'placeholder' => 'Напишіть вашу думку...'])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Надіслати коментар', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Будь ласка, <a href="<?= \yii\helpers\Url::to(['site/login']) ?>">увійдіть (Login)</a>, щоб залишити коментар.
            </div>
        <?php endif; ?>

    </div>

</div>