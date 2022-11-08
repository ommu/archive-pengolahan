<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:12 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

!$small ? \ommu\archive\assets\AciTreeAsset::register($this) : '';

if (!$small) {
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schema'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model::htmlHardDecode($model->title);
} ?>

<div class="archive-pengolahan-schema-view">

<?php
$treeDataUrl = Url::to(['data', 'id' => $model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
!$small ? $this->registerJs($js, \yii\web\View::POS_HEAD) : '';

$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'parentTitle',
		'value' => function ($model) {
            $parent = $model->parent;
            return $model::parseParent($parent);
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'code',
		'value' => $model->code ? $model->code : '-',
	],
	[
		'attribute' => 'title',
		'value' => $model::htmlHardDecode($model->title) ? $model::htmlHardDecode($model->title) : '-',
	],
	[
		'attribute' => 'archiveTitle',
		'value' => function ($model) {
            $archiveTitle = isset($model->archive) ? $model->archive->title : '-';
            if ($archiveTitle != '-') {
                return Html::a($archiveTitle, ['archive/view', 'id' => $model->archive_id], ['title' => $archiveTitle, 'class' => 'modal-btn']);
            }
            return $archiveTitle;
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oChild',
		'value' => function ($model) {
            $cards = $model->getChilds(true);
            return Html::a($cards, ['schema/admin/manage', 'parent' => $model->primaryKey], ['title' => Yii::t('app', '{count} childs', ['count' => $cards])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm modal-btn']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>