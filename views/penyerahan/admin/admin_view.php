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
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
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
		'format' => 'html',
	],
	[
        'attribute' => 'creator',
		'value' => function ($model) {
            return implode(', ', $model->getCreators(true, 'title'));
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'pencipta_arsip',
		'value' => $model->pencipta_arsip ? $model->pencipta_arsip : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'tahun',
		'value' => $model->tahun ? $model->tahun : '-',
	],
	[
		'attribute' => 'nomor_arsip',
		'value' => $model->nomor_arsip ? $model->nomor_arsip : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'jumlah_arsip',
		'value' => $model->jumlah_arsip ? $model->jumlah_arsip : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'nomor_box',
		'value' => $model->nomor_box ? $model->nomor_box : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'jumlah_box',
		'value' => $model->jumlah_box ? $model->jumlah_box : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'nomor_box_urutan',
		'value' => $model->nomor_box_urutan ? $model->nomor_box_urutan : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'lokasi',
		'value' => $model->lokasi ? $model->lokasi : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'jenisArsip',
		'value' => $model::parseJenisArsip($model->getJenis(false, 'title'), 'jenis', ', '),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'color_code',
		'value' => $model->color_code ? $model->color_code : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'oItem',
		'value' => function ($model) {
            $items = $model->grid->item;
            return Html::a($items, ['penyerahan/item/manage', 'penyerahan' => $model->primaryKey], ['title' => Yii::t('app', '{count} items', ['count' => $items]), 'data-pjax' => 0]);
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'oCard',
		'value' => function ($model) {
            $cards = $model->grid->card;
            return Html::a($cards, ['penyerahan/card/manage', 'penyerahan' => $model->primaryKey], ['title' => Yii::t('app', '{count} cards', ['count' => $cards]), 'data-pjax' => 0]);
		},
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'publication_file',
		'value' => function ($model) {
			$uploadPath = $model::getUploadPath(false);
			return $model->publication_file ? Html::a($model->publication_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->publication_file])), ['alt' => $model->publication_file, 'target' => '_blank']): '-';
		},
		'format' => 'raw',
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