<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редагування профілю';
$this->params['breadcrumbs'][] = ['label' => 'Мій профіль', 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-edit">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Налаштування профілю</h4>
                </div>
                <div class="card-body">

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <hr>
                    <div class="border rounded p-3 mb-3">
                        <i class="bi bi-shield-lock"></i> <strong>Безпека</strong>
                        <?= $form->field($model, 'new_password')->passwordInput()->hint('Залиште це поле пустим, якщо не хочете змінювати пароль') ?>
                    </div>
                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Ваш поточний аватар:</label><br>
                        <?php
                        $avatar = $model->image
                                ? Yii::getAlias('@web/uploads/') . $model->image
                                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                        ?>
                        <img src="<?= $avatar ?>" class="rounded-circle mb-2" width="100" height="100" style="object-fit: cover;">
                    </div>

                    <?= $form->field($model, 'imageFile')->fileInput() ?>

                    <div class="d-grid gap-2 mt-4">
                        <?= Html::submitButton('Зберегти зміни', ['class' => 'btn btn-success btn-lg']) ?>
                        <?= Html::a('Скасувати', ['profile'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>