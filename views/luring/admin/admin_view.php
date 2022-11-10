<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\luring\AdminController
 * @var $model ommu\archivePengolahan\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 17:23 WIB
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
    $this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Location'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $isFond ? $model->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($model->levelTitle->message), 'code' => $model->code]);
} ?>

<div class="archives-view">

<?php 
$treeDataUrl = Url::to(['/archive/admin/data', 'id' => $model->id]);
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
		'value' => $model::getPublish($model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'parent_id',
		'value' => function ($model) {
            $parent = $model->parent;
            return $model::parseParent($parent);
		},
		'format' => 'raw',
		'visible' => !$small && !$isFond,
	],
	[
		'attribute' => 'level_id',
		'value' => isset($model->levelTitle) ? $model->levelTitle->message : '-',
	],
	[
		'attribute' => 'code',
		'value' => $model->code ? $model->code : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creator',
		'value' => $model::parseRelated($model->getCreators(true, 'title'), null, ', '),
		'format' => 'html',
		'visible' => (!$small && in_array('creator', $model->level->field)) || ($small && $model->isFond) ? true : false,
	],
	[
		'attribute' => 'archive_date',
		'value' => $model->archive_date ? $model->archive_date : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'medium',
		'value' => $model->medium ? $model->medium : '-',
		'visible' => !$small && !$isFond,
	],
	[
		'attribute' => 'media',
		'value' => $model::parseRelated($model->getMedias(true, 'title'), null, ', '),
		'format' => 'html',
		'visible' => !$small && in_array('media', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'archive_type',
		'value' => $model::getArchiveType($model->archive_type ? $model->archive_type : '-'),
		'visible' => !$small && in_array('archive_type', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'location',
		'value' => function ($model) {
            if (($location = $model->getLocations(false)) != null) {
                return $model::parseLocation($location);
            }
			return Html::a(Yii::t('app', 'Add archive location'), ['set', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Add archive location'), 'class' => 'modal-btn']);
		},
		'format' => 'html',
		'visible' => !$small && !$isFond,
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
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>