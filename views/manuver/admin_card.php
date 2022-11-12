<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\ManuverController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 23:35 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manuver Kartu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Manuver');
?>

<div class="row">
    <div class="col-md-4 col-sm-5 col-xs-12">
        <?php echo $this->renderWidget('_schema', [
            'title' => Yii::t('app', 'Schema'),
            'model' => $model, 
			'fondId' => $fondId,
        ]);?>
    </div>

	<div class="col-md-8 col-sm-7 col-xs-12">
        <?php echo $this->renderWidget('_grid', [
            'title' => Yii::t('app', 'Cards'),
            'model' => $model, 
        ]);?>
    </div>
</div>