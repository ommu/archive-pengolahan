<?php
/**
 * Archive Pengolahan Finals (archive-pengolahan-final)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\FinalController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanFinal
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 13 November 2022, 12:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-final-form">

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

<?php echo $form->field($model, 'fond_number')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('fond_number')); ?>

<?php echo $form->field($model, 'fond_name')
    ->textarea(['rows' => 3, 'cols' => 50])
    ->label($model->getAttributeLabel('fond_name')); ?>

<?php echo $form->field($model, 'archive_start_from')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('archive_start_from')); ?>

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
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Finalisasi'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>