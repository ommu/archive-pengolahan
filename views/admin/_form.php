<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\AdminController
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

<?php $type = ArchivePengolahanPenyerahanType::getType();
echo $form->field($model, 'type_id')
	->dropDownList($type, ['prompt' => ''])
	->label($model->getAttributeLabel('type_id')); ?>

<?php echo $form->field($model, 'kode_box')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('kode_box')); ?>

<?php echo $form->field($model, 'pencipta_arsip')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('pencipta_arsip')); ?>

<?php echo $form->field($model, 'tahun')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('tahun')); ?>

<?php echo $form->field($model, 'nomor_arsip')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('nomor_arsip')); ?>

<?php echo $form->field($model, 'jumlah_arsip')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('jumlah_arsip')); ?>

<?php echo $form->field($model, 'nomor_box')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('nomor_box')); ?>

<?php echo $form->field($model, 'jumlah_box')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('jumlah_box')); ?>

<?php echo $form->field($model, 'nomor_box_urutan')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('nomor_box_urutan')); ?>

<?php echo $form->field($model, 'lokasi')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('lokasi')); ?>

<hr/>

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

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Penyerahan'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>