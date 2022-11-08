<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:12 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

\ommu\archive\assets\AciTreeAsset::register($this);

$treeId = $model->id;
if ($model->isNewRecord && $parent && !$model->getErrors()) {
    $model->parent_id = $parent->id;
    $treeId = $model->parent_id;
}
$treeDataUrl = Url::to(['data', 'id' => $treeId]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->parent_id';
JS;
	$this->registerJs($js, \yii\web\View::POS_HEAD);

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor']
];
?>

<div class="archive-pengolahan-schema-form">

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
echo $form->field($model, 'parent_id', ['template' => '{label}{beginWrapper}<div id="tree" class="aciTree"></div>{input}{error}{hint}{endWrapper}'])
	->hiddenInput()
	->label($model->getAttributeLabel('parent_id')); ?>

<?php echo $form->field($model, 'code')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('code')); ?>

<?php echo $form->field($model, 'title')
    ->textarea(['rows' => 6, 'cols' => 50])
    ->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
    ->label($model->getAttributeLabel('title')); ?>

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
        ->label(Yii::t('app', 'Stay on this page after I click {message}.', ['message' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')])); ?>
<hr/>
<?php }?>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Schema'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>