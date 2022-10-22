<?php
/**
 * Archive Pengolahan Imports (archive-pengolahan-import)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\ImportController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanImport
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 21 October 2022, 06:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Imports'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->original_filename;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-pengolahan-import-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'type',
		'value' => $model::getType($model->type),
		'visible' => !$small,
	],
	[
		'attribute' => 'original_filename',
		'value' => $model->original_filename ? $model->original_filename : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'custom_filename',
		'value' => function ($model) {
            $uploadPath = join('/', [ArchivePengolahanPenyerahan::getUploadPath(false), '_import']);
            return Html::a($model->custom_filename, Url::to(join('/', ['@webpublic', $uploadPath, $model->custom_filename])), ['title' => $model->custom_filename, 'data-pjax' => 0, 'target' => '_blank']);
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'all',
		'value' => $model->all ? $model->all : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'error',
		'value' => $model->error ? $model->error : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'log',
		'value' => $model->log ? $model->log : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'rollback',
		'value' => $model->filterYesNo($model->rollback),
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
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>