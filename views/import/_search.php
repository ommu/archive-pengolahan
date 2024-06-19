<?php
/**
 * Archive Pengolahan Imports (archive-pengolahan-import)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\ImportController
 * @var $model ommu\archivePengolahan\models\search\ArchivePengolahanImport
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 21 October 2022, 06:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\archivePengolahan\models\ArchivePengolahanImport;
?>

<div class="archive-pengolahan-import-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $type = $model::getType();
			echo $form->field($model, 'type')
			->dropDownList($type, ['prompt' => '']);?>

		<?php echo $form->field($model, 'original_filename');?>

		<?php echo $form->field($model, 'custom_filename');?>

		<?php echo $form->field($model, 'all');?>

		<?php echo $form->field($model, 'error');?>

		<?php echo $form->field($model, 'log');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'rollback')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>