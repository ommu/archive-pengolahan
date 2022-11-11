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
 * @created date 11 November 2022, 19:01 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

\ommu\archivePengolahan\components\assets\ArchiveTree::register($this);

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schema'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model::htmlHardDecode($model->title), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Tree');

if ($sync) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Back to Sync Schema'), 'url' => Url::to(['schema/sync/index']), 'icon' => 'arrow-left', 'htmlOptions' => ['class' => 'btn btn-warning']],
    ];
}

$treeDataUrl = Url::to(['schema/admin/manuver', 'id' => $model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->parent_id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>

<div class="archive-pengolahan-schema-card-create">
    <div id="tree" class="aciTree"></div>
</div>
