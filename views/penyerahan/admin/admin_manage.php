<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 * @var $searchModel ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahan
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Penyerahan'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
    ['label' => Yii::t('app', 'Import Penyerahan'), 'url' => Url::to(['import']), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-dark']],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Import Histories'), 'url' => Url::to(['import/manage', 'type' => 'penyerahan'])],
];
?>

<div class="archive-pengolahan-penyerahan-manage">
<?php Pjax::begin(); ?>

<?php if ($type != null) {
	echo $this->render('/setting/type/admin_view', ['model' => $type, 'small' => true]);
} ?>

<?php if ($jenis != null) {
	echo $this->render('@ommu/core/views/tag/admin_view', ['model' => $jenis, 'small' => true]);
} ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Penyerahan'), 'data-pjax' => 0]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Penyerahan'), 'data-pjax' => 0]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Penyerahan'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>