<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редагування профілю';
$this->params['breadcrumbs'][] = ['label' => 'Мій профіль', 'url' => ['site/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile-edit">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Налаштування профілю</h4>
                </div>
                <div class="card-body">

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Ваше ім\'я (Логін)') ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <div class="mb-3">
                        <label class="form-label">Аватар</label>
                        <div class="d-flex align-items-center mb-3">
                            <?php
                            $avatar = $model->image
                                ? Yii::getAlias('@web/uploads/') . $model->image
                                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                            ?>
                            <img src="<?= $avatar ?>" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #444;">

                            <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control'])->label(false) ?>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('Зберегти зміни', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Скасувати', ['site/profile'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </div>
</div>