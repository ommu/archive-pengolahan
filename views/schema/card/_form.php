<?php
/**
 * Archive Pengolahan Schema Cards (archive-pengolahan-schema-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchemaCard
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-schema-card-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'card_id')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('card_id')); ?>

<?php echo $form->field($model, 'fond_schema_id')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('fond_schema_id')); ?>

<?php echo $form->field($model, 'schema_id')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('schema_id')); ?>

<?php echo $form->field($model, 'final_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('final_id')); ?>

<?php echo $form->field($model, 'fond_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('fond_id')); ?>

<?php echo $form->field($model, 'archive_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('archive_id')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<?php if (($stayInHere = Yii::$app->request->get('stayInHere')) != null) {
    $model->stayInHere = $stayInHere;
}
if (!Yii::$app->request->isAjax) {
    echo $form->field($model, 'stayInHere')
        ->checkbox()
        ->label(Yii::t('app', 'Stay on this page after I click {message}.', ['message' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')])); ?>
<hr/>
<?php }?>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Manuver Kartu'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>