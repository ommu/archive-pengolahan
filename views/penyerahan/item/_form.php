<?php
/**
 * Archive Pengolahan Penyerahan Items (archive-pengolahan-penyerahan-item)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\ItemController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:19 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor']
];
?>

<div class="archive-pengolahan-penyerahan-item-form">

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

<hr />

<?php echo $form->field($model, 'archive_number')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('archive_number')); ?>

<?php echo $form->field($model, 'archive_description')
    ->textarea(['rows' => 6, 'cols' => 50])
    ->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
    ->label($model->getAttributeLabel('archive_description')); ?>

<?php echo $form->field($model, 'year')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('year')); ?>

<?php echo $form->field($model, 'volume')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('volume')); ?>

<?php echo $form->field($model, 'code')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('code')); ?>

<?php echo $form->field($model, 'description')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('description')); ?>

<hr/>

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
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Penyerahan Item'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>