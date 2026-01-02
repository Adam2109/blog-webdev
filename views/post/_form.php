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

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?> <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php // echo $form->field($model, 'viewed')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?php

    echo $form->field($model, 'status')->dropDownList([
            0 => 'Чорнетка',
            1 => 'Опубліковано'
    ]);
    ?>

    <?php

    $categories = Category::find()->all();
    $listData = ArrayHelper::map($categories, 'id', 'title');

    echo $form->field($model, 'category_id')->dropDownList(
            $listData,
            ['prompt' => 'Оберіть категорію...']
    );
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>