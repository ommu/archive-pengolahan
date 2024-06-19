<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\LocationController
 * @var $model ommu\archiveLocation\models\ArchiveLocations
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 23:36 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;
use yii\web\JsExpression;
use app\components\widgets\ActiveForm;
use ommu\archiveLocation\models\ArchiveLocationBuilding;
use ommu\archiveLocation\models\ArchiveLocationStorage;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => $model->archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Location'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->archive->isFond ? $model->archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($model->archive->levelTitle->message), 'code' => $model->archive->code]), 'url' => ['view', 'id' => $model->archive_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Set');

if (!$newRecord) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Reset Location'), 'url' => Url::to(['reset', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to reset location this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
}

if (!in_array('location', $model->archive->level->field)) {
	unset($this->params['menu']['content']['location']);
}

$js = <<<JS
	var building, depo, room, storage;
	var v_depo = '$model->depo_id';
	var v_room = '$model->room_id';
	var v_rack = '$model->rack_id';
	var v_storage = '$model->storage_id';
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
?>

<div class="archives-location">
	<div class="archives-form">

	<?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal form-label-left'],
		'enableClientValidation' => false,
		'enableAjaxValidation' => false,
		//'enableClientScript' => true,
	]);?>

	<?php $getDepoUrl = Url::to(['/archive-location/depo/suggest']);
	echo $form->field($model, 'building_id')
		->widget(Selectize::className(), [
			'cascade' => true,
			'options' => [
				'placeholder' => Yii::t('app', 'Select a building..'),
			],
			'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a building..')], ArchiveLocationBuilding::getLocation(['publish' => 1, 'type' => 'building'])),
			'pluginOptions' => [
				'onChange' => new JsExpression('function(value) {
                    if (!value.length) return;
					depo_id.disable();
					depo_id.clearOptions();
					depo_id.load(function(callback) {
						building && building.abort();
						building = $.ajax({
							url: \''.$getDepoUrl.'\',
							data: {\'parent\': value},
							success: function(results) {
								depo_id.removeOption(v_depo);
								depo_id.showInput();
								depo_id.enable();
								callback(results);
							},
							error: function() {
								callback();
							}
						})
					});
				}'),
			],
		])
		->label($model->getAttributeLabel('building_id')); ?>
		
	<?php $getRoomUrl = Url::to(['/archive-location/room/suggest']);
	$depo = $model->isNewRecord ? ArchiveLocationBuilding::getLocation(['publish' => 1, 'type' => 'depo']) : ArchiveLocationBuilding::getLocation(['publish' => 1, 'parent_id' => $model->building_id, 'type' => 'depo']);
	echo $form->field($model, 'depo_id')
		->widget(Selectize::className(), [
			'cascade' => true,
			'options' => [
				'placeholder' => Yii::t('app', 'Select a depo..'),
			],
			'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a depo..')], $depo),
			'pluginOptions' => [
				'valueField' => 'id',
				'labelField' => 'label',
				'searchField' => ['label'],
				'persist' => false,
				'onChange' => new JsExpression('function(value) {
					v_depo = value;
                    if (!value.length) return;
					room_id.disable();
					room_id.clearOptions();
					room_id.load(function(callback) {
						depo && depo.abort();
						depo = $.ajax({
							url: \''.$getRoomUrl.'\',
							data: {\'parent\': value},
							success: function(results) {
								room_id.removeOption(v_room);
								room_id.showInput();
								room_id.enable();
								callback(results);
							},
							error: function() {
								callback();
							}
						})
					});
				}'),
			],
		])
		->label($model->getAttributeLabel('depo_id')); ?>
		
	<?php 
	$getRackUrl = Url::to(['/archive-location/rack/suggest']);
	$getRoomStorageUrl = Url::to(['/archive-location/room/storage']);
	$room = $model->isNewRecord ? ArchiveLocationBuilding::getLocation(['publish' => 1, 'type' => 'room']) : ArchiveLocationBuilding::getLocation(['publish' => 1, 'parent_id' => $model->depo_id, 'type' => 'room']);
	echo $form->field($model, 'room_id')
		->widget(Selectize::className(), [
			'cascade' => true,
			'options' => [
				'placeholder' => Yii::t('app', 'Select a room..'),
			],
			'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a room..')], $room),
			'pluginOptions' => [
				'valueField' => 'id',
				'labelField' => 'label',
				'searchField' => ['label'],
				'persist' => false,
				'onChange' => new JsExpression('function(value) {
					v_room = value;
                    if (!value.length) return;
					rack_id.disable();
					rack_id.clearOptions();
					rack_id.load(function(callback) {
						depo && depo.abort();
						depo = $.ajax({
							url: \''.$getRackUrl.'\',
							data: {\'parent\': value},
							success: function(results) {
								rack_id.removeOption(v_rack);
								rack_id.showInput();
								rack_id.enable();
								callback(results);
							},
							error: function() {
								callback();
							}
						})
					});
					storage_id.disable();
					storage_id.clearOptions();
					storage_id.load(function(callback) {
						storage && storage.abort();
						storage = $.ajax({
							url: \''.$getRoomStorageUrl.'\',
							data: {\'id\': value},
							success: function(results) {
								storage_id.removeOption(v_storage);
								storage_id.showInput();
								storage_id.enable();
								callback(results);
							},
							error: function() {
								callback();
							}
						})
					});
				}'),
			],
		])
		->label($model->getAttributeLabel('room_id')); ?>

	<?php $rack = $model->isNewRecord ? ArchiveLocationBuilding::getLocation(['publish' => 1, 'type' => 'rack']) : ArchiveLocationBuilding::getLocation(['publish' => 1, 'parent_id' => $model->room_id, 'type' => 'rack']);
	echo $form->field($model, 'rack_id')
		->widget(Selectize::className(), [
			'cascade' => true,
			'options' => [
				'placeholder' => Yii::t('app', 'Select a rack..'),
			],
			'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a rack..')], $rack),
			'pluginOptions' => [
				'valueField' => 'id',
				'labelField' => 'label',
				'searchField' => ['label'],
				'persist' => false,
				'onChange' => new JsExpression('function(value) {
					v_rack = value;
				}'),
			],
		])
		->label($model->getAttributeLabel('rack_id'));?>

	<?php $storage = $model->isNewRecord ? ArchiveLocationStorage::getStorage(1) : $model->room->getRoomStorage(true, 'title');
	echo $form->field($model, 'storage_id')
		->widget(Selectize::className(), [
			'cascade' => true,
			'options' => [
				'placeholder' => Yii::t('app', 'Select a storage..'),
			],
			'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a storage..')], $storage),
			'pluginOptions' => [
				'valueField' => 'id',
				'labelField' => 'label',
				'searchField' => ['label'],
				'persist' => false,
				'onChange' => new JsExpression('function(value) {
					v_storage = value;
				}'),
			],
		])
		->label($model->getAttributeLabel('storage_id'));?>

	<?php echo $form->field($model, 'weight')
		->textInput(['maxlength' => true])
		->label($model->getAttributeLabel('weight'))
		->hint(Yii::t('app', 'Weight in grams')); ?>

	<?php echo $form->field($model, 'location_desc')
		->textarea(['rows' => 4, 'cols' => 50])
		->label($model->getAttributeLabel('location_desc')); ?>

	<hr/>

	<?php echo $form->field($model, 'submitButton')
		->submitButton(); ?>

	<?php ActiveForm::end(); ?>

	</div>
</div>
