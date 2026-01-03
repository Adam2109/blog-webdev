<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'content')->textarea(['id' => 'summernote']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control']) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'user_id')->textInput() ?>

    <hr>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList([
                    1 => 'Опубліковано',
                    0 => 'Чернетка (приховано)',
            ], ['class' => 'form-select']) ?>
        </div>

        <div class="col-md-4">
            <?php
            $categories = Category::find()->all();
            $listData = ArrayHelper::map($categories, 'id', 'title');

            echo $form->field($model, 'category_id')->dropDownList(
                    $listData,
                    ['prompt' => 'Оберіть категорію...', 'class' => 'form-select']
            );
            ?>
        </div>
    </div>
    <br>

    <?= $form->field($model, 'tagsInput')->textInput(['placeholder' => 'Введіть теги через кому (напр.: IT, News, Yii2)']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Зберегти статтю', ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $('#summernote').summernote({
        placeholder: 'Пишіть текст вашої статті тут...',
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>