<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Post $model */
/** @var app\models\Comment $commentForm */

$this->title = $model->title;

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

        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
            <div class="mb-3">
                <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                                'confirm' => 'Ви впевнені, що хочете видалити цю статтю?',
                                'method' => 'post',
                        ],
                ]) ?>
            </div>
            <hr>
        <?php endif; ?>

        <div class="post-content" style="font-size: 1.1em; line-height: 1.6; margin-bottom: 30px;">
            <?= nl2br(Html::encode($model->content)) ?>
        </div>

        <hr>

        <div class="d-flex align-items-center mt-4 mb-5 flex-wrap">

            <button class="btn btn-outline-danger me-4 like-btn" data-id="<?= $model->id ?>" id="like-btn">
                <i class="bi <?= $model->isLikedByCurrentUser() ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                <span id="like-text"><?= $model->isLikedByCurrentUser() ? 'Вподобано' : 'Вподобати' ?></span>
                <span class="badge bg-danger ms-1" id="likes-count"><?= $model->getLikesCount() ?></span>
            </button>

            <div class="social-share">
                <span class="h5 me-2 align-middle">Поділитися:</span>
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
        </div>
        <?php if (!empty($relatedPosts)): ?>
            <div class="related-posts mt-5 mb-5">
                <h4 class="mb-4 border-bottom pb-2 text-white">Читайте також по темі:</h4>
                <div class="row">
                    <?php foreach ($relatedPosts as $relPost): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm" style="background-color: #2c2c2c; border: 1px solid #444;">

                                <a href="<?= Url::to(['post/view', 'id' => $relPost->id]) ?>">
                                    <?php if ($relPost->image): ?>
                                        <img src="<?= Yii::getAlias('@web/uploads/') . $relPost->image ?>"
                                             class="card-img-top"
                                             alt="<?= Html::encode($relPost->title) ?>"
                                             style="height: 160px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center"
                                             style="height: 160px; background-color: #3a3a3a;">
                                            <i class="bi bi-image text-muted display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <div class="card-body">
                                    <h6 class="card-title" style="min-height: 40px;">
                                        <a href="<?= Url::to(['post/view', 'id' => $relPost->id]) ?>"
                                           class="text-decoration-none text-info fw-bold">
                                            <?= Html::encode($relPost->title) ?>
                                        </a>
                                    </h6>

                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        <?= Yii::$app->formatter->asDate($relPost->date, 'medium') ?>
                                    </div>

                                    <p class="card-text small text-secondary">
                                        <?= \yii\helpers\StringHelper::truncate(strip_tags($relPost->description), 60) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="comments-section bg-dark-custom p-4 rounded">
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

                                    <?php if (!Yii::$app->user->isGuest && ($comment->user && Yii::$app->user->id == $comment->user->id || Yii::$app->user->identity->isAdmin())): ?>
                                        <?= Html::a('<i class="bi bi-trash"></i>', ['post/delete-comment', 'id' => $comment->id], [
                                                'class' => 'text-danger ms-2 text-decoration-none',
                                                'title' => 'Видалити коментар',
                                                'data' => [
                                                        'confirm' => 'Ви впевнені? Це також видалить усі відповіді на цей коментар.',
                                                        'method' => 'post',
                                                ],
                                        ]) ?>
                                    <?php endif; ?>
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
    
    $('#like-btn').on('click', function() {
        var btn = $(this);
        var id = btn.data('id');
        
        $.ajax({
            url: 'index.php?r=post/like&id=' + id,
            type: 'POST',
            success: function(data) {
                if (data.success) {
                    // 1. Оновлюємо цифру лайків
                    $('#likes-count').text(data.likesCount);
                    
                    // 2. Змінюємо вигляд кнопки (сердечко та текст)
                    if (data.isLiked) {
                        btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                        $('#like-text').text('Вподобано');
                    } else {
                        btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                        $('#like-text').text('Вподобати');
                    }
                } else {
                    alert('Будь ласка, увійдіть (Login), щоб ставити лайки.');
                }
            },
            error: function() {
                console.log('Error with like request');
            }
        });
    });

  
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