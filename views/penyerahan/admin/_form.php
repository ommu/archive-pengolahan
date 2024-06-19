<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
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
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('kode_box')); ?>

<hr/>

<?php echo $form->field($model, 'pencipta_arsip')
	->textarea(['rows' => 3, 'cols' => 50])
	->label($model->getAttributeLabel('pencipta_arsip')); ?>

<?php
$creatorSuggestUrl = Url::to(['/archive/setting/creator/suggest']);
echo $form->field($model, 'creator', ['options' => ['class' => 'form-group row field-item']])
    ->widget(Selectize::className(), [
        'cascade' => true,
        'url' => $creatorSuggestUrl,
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
    ->label($model->getAttributeLabel('creator'));
    // ->hint(Yii::t('app', 'Record the name of the organization(s) or the individual(s) responsible for the creation, accumulation and maintenance of the records in the unit of description. Search for an existing name in the authority records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new authority record.')); ?>

<hr/>

<?php echo $form->field($model, 'tahun')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('tahun')); ?>

<?php echo $form->field($model, 'nomor_arsip')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('nomor_arsip')); ?>

<?php echo $form->field($model, 'jumlah_arsip')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('jumlah_arsip')); ?>

<?php echo $form->field($model, 'nomor_box')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('nomor_box')); ?>

<?php echo $form->field($model, 'jumlah_box')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('jumlah_box')); ?>

<?php echo $form->field($model, 'nomor_box_urutan')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('nomor_box_urutan')); ?>

<?php echo $form->field($model, 'lokasi')
	->textarea(['rows' => 2, 'cols' => 50])
	->label($model->getAttributeLabel('lokasi')); ?>

<hr/>

<?php
$subjectSuggestUrl = Url::to(['setting/jenis/suggest']);
echo $form->field($model, 'jenisArsip', ['options' => ['class' => 'form-group row field-item']])
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
	->label($model->getAttributeLabel('jenisArsip'));?>

<hr/>

<?php echo $form->field($model, 'color_code')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('color_code')); ?>

<?php echo $form->field($model, 'description')
	->textarea(['rows' => 2, 'cols' => 50])
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
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Penyerahan'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>