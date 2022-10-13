<?php
/**
 * Archive Pengolahan Penyerahan Types (archive-pengolahan-penyerahan-type)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\setting\TypeController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 07:52 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-penyerahan-type-form">

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

<?php echo $form->field($model, 'type_name')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('type_name')); ?>

<?php echo $form->field($model, 'type_desc')
	->textarea(['rows' => 4, 'cols' => 50, 'maxlength' => true])
	->label($model->getAttributeLabel('type_desc')); ?>

<hr/>

<?php $field = $model::getField('field');
echo $form->field($model, 'field')
	->checkboxList($field)
	->label($model->getAttributeLabel('field')); ?>

<hr/>

<?php $feature = $model::getField('feature');
echo $form->field($model, 'feature')
	->checkboxList($feature)
	->label($model->getAttributeLabel('feature')); ?>

<hr/>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Penyerahan Type'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>