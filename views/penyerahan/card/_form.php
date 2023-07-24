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
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;
use ommu\archive\models\ArchiveMedia;
use ommu\selectize\Selectize;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor']
];
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

<?php echo $form->errorSummary($model);?>

<?php
$parsePenyerahan = $penyerahan::parsePenyerahan($penyerahan, true);
echo $form->field($model, 'penyerahan_id', ['template' => '{label}{beginWrapper}{input}{error}{hint}'.$parsePenyerahan.'{endWrapper}'])
	->hiddenInput()
	->label($model->getAttributeLabel('penyerahan_id')) ?>

<hr/>

<?php 
$parseUser = $user ? 
    $user::parseUser($user) : 
    Yii::t('app', 'Your account not have permission to add a description card, please contact administrator.');
echo $form->field($model, 'user_id', ['template' => '{label}{beginWrapper}{input}{error}{hint}'.$parseUser.'{endWrapper}'])
	->hiddenInput()
	->label($model->getAttributeLabel('user_id')); ?>

<hr/>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->temporary_number = $user ? $user->user_code .'/'. ($user->archives + 1) : null;
}
echo $form->field($model, 'temporary_number', ['template' => '{label}{beginWrapper}{input}{error}{hint}'.$model->temporary_number.'{endWrapper}'])
    ->hiddenInput()
	->label($model->getAttributeLabel('temporary_number')); ?>

<hr/>

<?php echo $form->field($model, 'archive_description')
    ->textarea(['rows' => 6, 'cols' => 50])
    ->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
    ->label($model->getAttributeLabel('archive_description')); ?>

<hr/>

<?php $archiveTypeFromMonth = $form->field($model, 'archive_date[from][month]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['type' => 'number', 'min' => 0, 'max' => 12, 'maxlength' => '2', 'placeholder' => $model->getAttributeLabel('month')])
	->label($model->getAttributeLabel('archive_date[from][month]'))
    ->hint($model->getAttributeLabel('month')); ?>

<?php $archiveTypeFromYear = $form->field($model, 'archive_date[from][year]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['type' => 'number', 'min' => 0, 'maxlength' => '4', 'placeholder' => $model->getAttributeLabel('year')])
	->label($model->getAttributeLabel('archive_date[from][year]'))
    ->hint($model->getAttributeLabel('year')); ?>

<?php echo $form->field($model, 'archive_date[from][day]', ['template' => '{label}{beginWrapper}{input}{hint}{endWrapper}'.$archiveTypeFromMonth . $archiveTypeFromYear .'{error}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4', 'error' => 'col-sm-9 col-xs-12']])
	->textInput(['type' => 'number', 'min' => 0, 'max' => 31, 'maxlength' => '2', 'placeholder' => $model->getAttributeLabel('day')])
	->label($model->getAttributeLabel('from_archive_date'))
    ->hint($model->getAttributeLabel('day')); ?>

<?php $archiveTypeToMonth = $form->field($model, 'archive_date[to][month]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['type' => 'number', 'min' => 0, 'max' => 12, 'maxlength' => '2', 'placeholder' => $model->getAttributeLabel('month')])
	->label($model->getAttributeLabel('archive_date[to][month]'))
    ->hint($model->getAttributeLabel('month')); ?>

<?php $archiveTypeToYear = $form->field($model, 'archive_date[to][year]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['type' => 'number', 'min' => 0, 'maxlength' => '4', 'placeholder' => $model->getAttributeLabel('year')])
	->label($model->getAttributeLabel('archive_date[to][year]'))
    ->hint($model->getAttributeLabel('year')); ?>

<?php echo $form->field($model, 'archive_date[to][day]', ['template' => '{label}{beginWrapper}{input}{hint}{endWrapper}'.$archiveTypeToMonth . $archiveTypeToYear .'{error}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4', 'error' => 'col-sm-9 col-xs-12']])
	->textInput(['type' => 'number', 'min' => 0, 'max' => 31, 'maxlength' => '2', 'placeholder' => $model->getAttributeLabel('day')])
	->label($model->getAttributeLabel('to_archive_date'))
    ->hint($model->getAttributeLabel('day')); ?>

<hr/>

<?php $archiveType = $model::getArchiveType();
echo $form->field($model, 'archive_type')
	->dropDownList($archiveType, ['prompt' => ''])
	->label($model->getAttributeLabel('archive_type')); ?>

<?php
echo $form->field($model, 'media', ['options' => ['class' => 'form-group row field-item']])
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => ArchiveMedia::getMedia(1),
		'options' => [
			'multiple' => true,
		],
		'pluginOptions' => [
			'plugins' => ['remove_button'],
		],
	])
	->label($model->getAttributeLabel('media'));?>

<?php $mediumUnit = $form->field($model, 'medium_json[unit]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['placeholder' => $model->getAttributeLabel('unit')])
	->label($model->getAttributeLabel('medium_json[unit]'))
    ->hint($model->getAttributeLabel('unit')); ?>

<?php $mediumCondition = $form->field($model, 'medium_json[condition]', ['template' => '{beginWrapper}{input}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4'], 'options' => ['tag' => null]])
	->textInput(['placeholder' => $model->getAttributeLabel('condition')])
	->label($model->getAttributeLabel('medium_json[condition]'))
    ->hint($model->getAttributeLabel('condition')); ?>

<?php echo $form->field($model, 'medium_json[total]', ['template' => '{label}{beginWrapper}{input}{hint}{endWrapper}'.$mediumUnit . $mediumCondition .'{error}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-3 col-xs-4', 'error' => 'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type' => 'number', 'placeholder' => $model->getAttributeLabel('total')])
	->label($model->getAttributeLabel('medium'))
    ->hint($model->getAttributeLabel('total')); ?>

<?php echo $form->field($model, 'developmental_level')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('developmental_level')); ?>

<hr/>

<?php
$subjectSuggestUrl = Url::to(['/admin/tag/suggest']);
echo $form->field($model, 'subject', ['options' => ['class' => 'form-group row field-item']])
	->widget(Selectize::className(), [
        'cascade' => true,
		'url' => $subjectSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('subject'));?>

<?php
echo $form->field($model, 'function', ['options' => ['class' => 'form-group row field-item']])
	->widget(Selectize::className(), [
		'url' => $subjectSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('function'));?>

<hr/>

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