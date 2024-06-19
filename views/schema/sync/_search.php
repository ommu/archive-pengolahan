<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\SyncController
 * @var $model ommu\archivePengolahan\models\search\Archives
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 21:46 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\archivePengolahan\models\Archives;
use ommu\archivePengolahan\models\ArchiveLevel;
?>

<div class="archives-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'parent_id');?>

		<?php $level = ArchiveLevel::getLevel();
		echo $form->field($model, 'level_id')
			->dropDownList($level, ['prompt' => '']);?>

		<?php echo $form->field($model, 'title');?>

		<?php echo $form->field($model, 'code');?>

		<?php echo $form->field($model, 'medium');?>

		<?php $archiveType = $model::getArchiveType();
			echo $form->field($model, 'archive_type')
			->dropDownList($archiveType, ['prompt' => '']);?>

		<?php echo $form->field($model, 'archive_date');?>

		<?php echo $form->field($model, 'archive_file');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'sidkkas')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'sync_schema')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>