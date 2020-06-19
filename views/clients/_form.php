<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model NIOLAB\oauth2\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'redirect_uri')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'secret',['options'=>['class'=>'has-warning form-group']])->label('<span style="color:#000">'.$model->getAttributeLabel('secret').'</span> '.Yii::t('oauth2','Store this secret securely, it will not be shown again!'))->textInput(['readonly'=>true,'maxlength' => true]) ?>

            <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right', 'name' => 'signup-button']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>