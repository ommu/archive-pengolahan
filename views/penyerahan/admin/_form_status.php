<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType;
use yii\helpers\ArrayHelper;
?>

<div class="archive-pengolahan-penyerahan-form">

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

<?php $status = [
    '1' =>  Yii::t('app', 'Sudah'),
    '0' =>  Yii::t('app', 'Belum'),
];
echo $form->field($model, 'pengolahan_status')
	->dropDownList($status, ['prompt' => ''])
	->label($model->getAttributeLabel('pengolahan_status')); ?>

<?php echo $form->field($model, 'pengolahan_tahun')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('pengolahan_tahun')); ?>

<hr/>

<?php $submitButtonOption = ['button' => Html::submitButton(Yii::t('app', 'Update Status'), ['class' => 'btn btn-primary'])];
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>