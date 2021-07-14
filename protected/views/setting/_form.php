<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    'id' => 'setting-form',
    'options' => [
        'class' => 'row'
    ]
]);
?>
	<div class="col-md-6 offset-md-3">
      <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
      <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
      <?php /*echo  $form->field($model, 'value')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'value')->textarea(['rows' => 6]); */ ?>
      <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
      <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>         
      <div class="text-center">
         <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'setting-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
	</div>
   <?php TActiveForm::end(); ?>
</div>