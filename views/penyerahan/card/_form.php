<?php
/**
 * Archive Pengolahan Penyerahan Cards (archive-pengolahan-penyerahan-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 11:25 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-penyerahan-card-form">

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

<?php
$parsePenyerahan = $penyerahan::parsePenyerahan($penyerahan, true);
echo $form->field($model, 'penyerahan_id', ['template' => '{label}{beginWrapper}{input}{error}{hint}'.$parsePenyerahan.'{endWrapper}'])
	->hiddenInput()
	->label($model->getAttributeLabel('penyerahan_id')) ?>

<hr/>

<?php echo $form->field($model, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<hr/>

<?php echo $form->field($model, 'temporary_number')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('temporary_number')); ?>

<?php $archiveType = $model::getArchiveType();
echo $form->field($model, 'archive_type')
	->dropDownList($archiveType, ['prompt' => ''])
	->label($model->getAttributeLabel('archive_type')); ?>

<?php echo $form->field($model, 'from_archive_date')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('from_archive_date')); ?>

<?php echo $form->field($model, 'to_archive_date')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('to_archive_date')); ?>

<?php
echo $form->field($model, 'medium', ['options' => ['class' => 'form-group row field-item']])
    ->textarea(['rows' => 2, 'cols' => 50])
    ->label($model->getAttributeLabel('medium'))
    ->hint(Yii::t('app', 'Record the extent of the unit of description by giving the number of physical or logical units in arabic numerals and the unit of measurement. Give the specific medium (media) of the unit of description. Separate multiple extents with a linebreak.')); ?>

<hr/>

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
        ->label(Yii::t('app', 'Stay on this page after I click {message}.', ['message' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')]));
} ?>

<hr/>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Description Card'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>