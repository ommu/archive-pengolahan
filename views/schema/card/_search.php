<?php
/**
 * Archive Pengolahan Schema Cards (archive-pengolahan-schema-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\CardController
 * @var $model ommu\archivePengolahan\models\search\ArchivePengolahanSchemaCard
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="archive-pengolahan-schema-card-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'cardPenyerahanId');?>

		<?php echo $form->field($model, 'fond_schema_id');?>

		<?php echo $form->field($model, 'schemaTitle');?>

		<?php echo $form->field($model, 'finalFondName');?>

		<?php echo $form->field($model, 'fondTitle');?>

		<?php echo $form->field($model, 'archiveTitle');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>