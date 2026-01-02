<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

    <p class="text-muted">
        <small>
            Дата: <?= Yii::$app->formatter->asDate($model->date, 'long') ?> |
            Категорія: <b><?= $model->category ? $model->category->title : 'Без категорії' ?></b>
        </small>
    </p>

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

    <div class="post-content" style="font-size: 1.1em; line-height: 1.6;">
        <?php
        echo nl2br(Html::encode($model->content));
        ?>
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