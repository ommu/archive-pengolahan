<?php
/**
 * Archive Pengolahan Penyerahan Items (archive-pengolahan-penyerahan-item)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\ItemController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem
 * @var $searchModel ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanItem
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:19 WIB
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
if ($penyerahan) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['penyerahan/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $penyerahan->type->type_name. ': ' .$penyerahan->kode_box, 'url' => ['penyerahan/admin/view', 'id' => $penyerahan->id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Items');
} else {
    $this->params['breadcrumbs'][] = $this->title;
}

$importHistoryUrl = ['import/manage', 'type' => 'item'];
if ($penyerahan) {
    $importHistoryUrl = ['import/manage', 'type' => 'item', 'id' => $penyerahan->id];
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Add Item'), 'url' => Url::to(['create', 'id' => $penyerahan->id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
        ['label' => Yii::t('app', 'Import Item'), 'url' => Url::to(['import', 'id' => $penyerahan->id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-dark']],
    ];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Import Histories'), 'url' => Url::to($importHistoryUrl)],
];
?>

<div class="archive-pengolahan-penyerahan-item-manage">
<?php Pjax::begin(); ?>

<?php if ($penyerahan != null) {
	echo $this->render('/penyerahan/admin/admin_view', ['model' => $penyerahan, 'small' => true]);
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
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Penyerahan Item'), 'class' => 'modal-btn']);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Penyerahan Item'), 'data-pjax' => 0]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Penyerahan Item'),
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