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
 * @created date 15 October 2022, 20:10 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['penyerahan/admin/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Import');
?>

<div class="archive-pengolahan-penyerahan-item-create">

<?php echo Html::beginForm(Yii::$app->request->absoluteUrl, 'post', [
	'class' => 'form-horizontal form-label-left',
	'enctype' => 'multipart/form-data',
	'onpost' => 'onpost',
]); ?>

<?php echo $this->description && Yii::$app->request->isAjax ? Html::tag('p', $this->description, ['class' => 'mb-4']) : '';?>

<div class="form-group row">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="importFilename"><?php echo Yii::t('app', 'Import File');?></label>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php echo Html::fileInput('importFilename', '', ['id' => 'importFilename']);?>
		<div class="help-block help-block-error">
			<?php echo Yii::t('app', 'extensions are allowed: {extensions}', ['extensions' => 'xlsx, xls']);?>
            <hr/>
            <?php echo Html::a(Yii::t('app', 'download template import penyerahan item'), $template, ['class' => 'btn btn-success btn-sm']);?>
		</div>
	</div>
</div>

<hr/>

<div class="form-group row">
	<div class="col-md-9 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-dark']);?>
	</div>
</div>

<?php echo Html::endForm(); ?>

</div>