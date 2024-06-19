<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\ManuverController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 * @var $searchModel ommu\archivePengolahan\models\search\ArchivePengolahanSchema
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 23:35 WIB
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

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="archive-pengolahan-schema-manage">
<?php Pjax::begin(); ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'card') {
            return Url::to(['card', 'id' => $key]);
        }
        if ($action == 'final') {
            return Url::to(['final', 'id' => $key]);
        }
	},
	'buttons' => [
		'card' => function ($url, $model, $key) {
			return Html::a(Yii::t('app', 'Manuver'), $url, ['title' => Yii::t('app', 'Manuver'), 'class' => 'btn btn-success btn-xs', 'data-pjax' => 0]);
		},
		'final' => function ($url, $model, $key) {
            $childs = $model->getCards(true, null, true);
            if ($childs) {
                return Html::a(Yii::t('app', 'Final'), $url, ['title' => Yii::t('app', 'Final'), 'class' => 'btn btn-warning btn-xs modal-btn']);
            }
		},
	],
	'template' => '{card} {final}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>