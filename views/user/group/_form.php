<?php
/**
 * Archive Pengolahan User Groups (archive-pengolahan-user-group)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\user\GroupController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanUserGroup
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 08:47 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-user-group-form">

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

<?php echo $form->field($model, 'name')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'permission')
	->textInput(['maxlength' => true, 'disabled' => !$model->isNewRecord ? true : false])
	->label($model->getAttributeLabel('permission')); ?>

<hr/>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish'));

if (($stayInHere = Yii::$app->request->get('stayInHere')) != null) {
    $model->stayInHere = $stayInHere;
}
echo $form->field($model, 'stayInHere')
	->checkbox()
	->label(Yii::t('app', 'Stay on this page after I click {message}.', ['message' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')])); ?>

<hr/>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail User Group'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>