<?php
/**
 * Archive Pengolahan Users (archive-pengolahan-users)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\user\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanUsers
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 10:10 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use ommu\archivePengolahan\models\ArchivePengolahanUserGroup;
use yii\web\JsExpression;
use ommu\selectize\Selectize;

$js = <<<JS
	var options = '';
	var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
		'(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
?>

<div class="archive-pengolahan-users-form">

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
$userSuggestUrl = Url::to(['/users/member/suggest']);
$userOptions = [
	'valueField' => 'id',
	'labelField' => 'name',
	'searchField' => ['name', 'email'],
	'maxItems' => '1',
	'persist' => false,
	'render' => [
		'item' => new JsExpression('function(item, escape) {
			return \'<div>\' +
				(item.name ? \'<span class="name">\' + escape(item.name) + \'</span>\' : \'\') +
				(item.email ? \'<span class="email">\' + escape(item.email) + \'</span>\' : \'\') +
			\'</div>\';
		}'),
		'option' => new JsExpression('function(item, escape) {
			var label = item.name || item.email;
			var caption = item.name ? item.email : null;
			return \'<div>\' +
				\'<span class="label">\' + escape(label) + \'</span>\' +
				(caption ? \'<span class="caption">\' + escape(caption) + \'</span>\' : \'\') +
			\'</div>\';
		}'),
	],
	'createFilter' => new JsExpression('function(input) {
		var match, regex;

		regex = new RegExp(\'^\' + REGEX_EMAIL + \'$\', \'i\');
		match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[0]);

		regex = new RegExp(\'^([^<]*)\<\' + REGEX_EMAIL + \'\>$\', \'i\');
		match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[2]);

		return false;
	}'),
	'create' => new JsExpression('function(input) {
        if ((new RegExp(\'^\' + REGEX_EMAIL + \'$\', \'i\')).test(input)) {
			return {email: input};
		}
		var match = input.match(new RegExp(\'^([^<]*)\<\' + REGEX_EMAIL + \'\>$\', \'i\'));
        if (match) {
			return {
				email : match[2],
				name  : $.trim(match[1])
			};
		}
		alert(\'Invalid email address.\');
		return false;
	}'),
	// 'onChange' => new JsExpression('function(value) {
	// 	options = this.options;
	// 	var userSelected = this.options[value];
	// 	$(\'form\').find(\'#speaker_name\').val(userSelected.name);
	// }'),
	'onDelete' => new JsExpression('function(value) {
		user_id.clear();
		user_id.clearOptions();
	}'),
];
if ($model->user_id == 0 && !$model->getErrors()) {
    $model->user_id = '';
}
if ($model->user_id && isset($model->user)) {
	$userOptions = ArrayHelper::merge($userOptions, [
		'options' => [[
			'id' => $model->user_id,
			'email' => trim($model->user->email),
			'name' => trim($model->user->displayname),
			'photo' => trim($model->user->photos),
		]]
	]);
}
echo $form->field($model, 'user_id')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Pick some people...'),
			'class' => 'form-control contacts',
		],
		'url' => $userSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => $userOptions,
	])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'user_code')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('user_code')); ?>

<hr/>

<?php $permission = ArchivePengolahanUserGroup::getPermission();
echo $form->field($model, 'groups')
	->checkboxList($permission)
	->label($model->getAttributeLabel('groups')); ?>

<hr/>

<?php
if (($stayInHere = Yii::$app->request->get('stayInHere')) != null) {
    $model->stayInHere = $stayInHere;
}
if (!$model->isNewRecord && !Yii::$app->request->isAjax) {
    echo $form->field($model, 'stayInHere')
        ->checkbox()
        ->label(Yii::t('app', 'Stay on this page after I click {message}.', ['message' => $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')]));  ?>

<hr/>
<?php }?>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail User'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>