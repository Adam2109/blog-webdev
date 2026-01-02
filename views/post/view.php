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
                <div class="card mb-3 shadow-sm <?= $comment->parent_id ? 'ms-5 border-start border-primary border-4' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title text-primary m-0">
                                <?= Html::encode($comment->user ? $comment->user->username : 'Гість') ?>
                                <?php if($comment->parent_id): ?>
                                    <small class="text-muted" style="font-size: 0.8em;">(відповідь)</small>
                                <?php endif; ?>
                            </h5>
                            <small class="text-muted">
                                <?= Yii::$app->formatter->asDate($comment->date, 'medium') ?>
                            </small>
                        </div>
                        <p class="card-text">
                            <?= Html::encode($comment->text) ?>
                        </p>

                        <?php if (!Yii::$app->user->isGuest): ?>
                            <button class="btn btn-sm btn-outline-secondary reply-btn"
                                    data-id="<?= $comment->id ?>"
                                    data-user="<?= Html::encode($comment->user->username) ?>">
                                ↩ Відповісти
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Поки немає коментарів. Будьте першим!</p>
        <?php endif; ?>

        <div class="mt-4">
            <h4 id="comment-label">Залишити коментар</h4>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="comment-form">
                    <?php $form = ActiveForm::begin([
                            'action' => ['post/view', 'id' => $model->id],
                    ]); ?>

                    <?= $form->field($commentForm, 'parent_id')->hiddenInput(['class' => 'parent-id-input'])->label(false) ?>

                    <?= $form->field($commentForm, 'text')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Напишіть вашу думку...',
                            'id' => 'comment-text-area'
                    ])->label(false) ?>

                    <div class="d-flex justify-content-between">
                        <?= Html::submitButton('Надіслати', ['class' => 'btn btn-success']) ?>

                        <button type="button" class="btn btn-link text-danger cancel-reply" style="display: none;">Скасувати відповідь</button>
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

<?php
$js = <<<JS
    
    $('.reply-btn').on('click', function() {
        var parentId = $(this).data('id');     
        var username = $(this).data('user');     
        
       
        $('.parent-id-input').val(parentId);
        
        $('#comment-label').text('Відповідь для ' + username);
        $('#comment-text-area').attr('placeholder', '@' + username + ', ');
        $('#comment-text-area').focus();
        
        
        $('.cancel-reply').show();
        
        $('html, body').animate({
            scrollTop: $(".comment-form").offset().top
        }, 500);
    });

    $('.cancel-reply').on('click', function() {
        $('.parent-id-input').val('');
        $('#comment-label').text('Залишити коментар');
        $('#comment-text-area').attr('placeholder', 'Напишіть вашу думку...');
        $(this).hide();
    });
JS;
$this->registerJs($js);
?>

</div>