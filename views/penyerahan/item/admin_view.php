<?php
/**
 * Archive Pengolahan Penyerahan Items (archive-pengolahan-penyerahan-item)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\ItemController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:19 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['penyerahan/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->type->type_name. ': ' .$model->penyerahan->kode_box, 'url' => ['penyerahan/admin/view', 'id' => $model->penyerahan_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item'), 'url' => ['manage', 'penyerahan' => $model->penyerahan_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-pengolahan-penyerahan-item-view">

<?php
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
		'attribute' => 'penyerahanTypeId',
		'value' => function ($model) {
            $penyerahanTypeId = isset($model->type) ? $model->type->type_name : '-';
            if ($penyerahanTypeId != '-') {
                return Html::a($penyerahanTypeId, ['setting/type/view', 'id' => $model->penyerahan->type_id], ['title' => $penyerahanTypeId, 'class' => 'modal-btn']);
            }
            return $penyerahanTypeId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'penyerahanPenciptaArsip',
		'value' => function ($model) {
            return $model->penyerahan::parsePenyerahan($model->penyerahan, true);
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'archive_number',
		'value' => $model->archive_number ? $model->archive_number : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'archive_description',
		'value' => $model->archive_description ? $model->archive_description : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'year',
		'value' => $model->year ? $model->year : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'volume',
		'value' => $model->volume ? $model->volume : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'code',
		'value' => $model->code ? $model->code : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
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