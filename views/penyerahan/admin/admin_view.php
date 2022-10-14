<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->type->type_name. ': ' .$model->kode_box;
} ?>

<div class="archive-pengolahan-penyerahan-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'typeName',
		'value' => function ($model) {
            $typeName = isset($model->type) ? $model->type->type_name : '-';
            if ($typeName != '-') {
                return Html::a($typeName, ['setting/type/view', 'id' => $model->type_id], ['title' => $typeName, 'class' => 'modal-btn']);
            }
            return $typeName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'kode_box',
		'value' => $model->kode_box ? $model->kode_box : '-',
	],
	[
		'attribute' => 'pencipta_arsip',
		'value' => $model->pencipta_arsip ? $model->pencipta_arsip : '-',
	],
	[
		'attribute' => 'tahun',
		'value' => $model->tahun ? $model->tahun : '-',
	],
	[
		'attribute' => 'nomor_arsip',
		'value' => $model->nomor_arsip ? $model->nomor_arsip : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'jumlah_arsip',
		'value' => $model->jumlah_arsip ? $model->jumlah_arsip : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'nomor_box',
		'value' => $model->nomor_box ? $model->nomor_box : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'jumlah_box',
		'value' => $model->jumlah_box ? $model->jumlah_box : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'nomor_box_urutan',
		'value' => $model->nomor_box_urutan ? $model->nomor_box_urutan : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'lokasi',
		'value' => $model->lokasi ? $model->lokasi : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'jenisArsip',
		'value' => $model::parseJenisArsip($model->getJenis(false, 'title'), 'jenis', ', '),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'pengolahan_status',
		'value' => $model->filterYesNo($model->pengolahan_status),
		'visible' => !$small,
	],
	[
		'attribute' => 'pengolahan_tahun',
		'value' => $model->pengolahan_tahun ? $model->pengolahan_tahun : '-',
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