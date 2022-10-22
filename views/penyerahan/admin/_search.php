<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahan
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType;
?>

<div class="archive-pengolahan-penyerahan-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $type = ArchivePengolahanPenyerahanType::getType();
		echo $form->field($model, 'type_id')
			->dropDownList($type, ['prompt' => '']);?>

		<?php echo $form->field($model, 'kode_box');?>

		<?php echo $form->field($model, 'pencipta_arsip');?>

		<?php echo $form->field($model, 'tahun');?>

		<?php echo $form->field($model, 'nomor_arsip');?>

		<?php echo $form->field($model, 'jumlah_arsip');?>

		<?php echo $form->field($model, 'nomor_box');?>

		<?php echo $form->field($model, 'jumlah_box');?>

		<?php echo $form->field($model, 'nomor_box_urutan');?>

		<?php echo $form->field($model, 'lokasi');?>

		<?php echo $form->field($model, 'color_code');?>

		<?php echo $form->field($model, 'description');?>

		<?php echo $form->field($model, 'publication_file');?>

		<?php echo $form->field($model, 'pengolahan_tahun');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'pengolahan_status')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>