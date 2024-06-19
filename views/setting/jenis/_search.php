<?php
/**
 * Archive Pengolahan Penyerahan Jenis (archive-pengolahan-penyerahan-jenis)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\setting\JenisController
 * @var $model ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanJenis
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 12 October 2022, 19:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="archive-pengolahan-penyerahan-jenis-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'penyerahanArsip');?>

		<?php echo $form->field($model, 'tagBody');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>