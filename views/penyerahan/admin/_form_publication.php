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
use yii\helpers\Url;
use ommu\selectize\Selectize;
?>

<div class="archive-pengolahan-penyerahan-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $uploadPath = $model::getUploadPath(false);
$publicationFile = !$model->isNewRecord && $model->old_publication_file != '' ? '<hr/>'.Html::a($model->old_publication_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->old_publication_file])), ['title'=>$model->old_publication_file, 'target' => '_blank', 'class' => 'd-inline-block mb-3']) : '';
echo $form->field($model, 'publication_file', ['template' => '{label}{beginWrapper}{input}{error}{hint}<div>'.$publicationFile.'</div>{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('publication_file')); ?>

<hr/>

<?php $submitButtonOption = ['button' => Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-primary'])];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Penyerahan'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>