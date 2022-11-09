<?php
/**
 * Archive Pengolahan Schema Cards (archive-pengolahan-schema-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchemaCard
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manuver Kartu'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->card->penyerahan->type->type_name;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-pengolahan-schema-card-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish, 'deleted'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'cardPenyerahanId',
		'value' => function ($model) {
            $cardPenyerahanId = isset($model->card) ? $model->card->penyerahan->type->type_name : '-';
            if ($cardPenyerahanId != '-') {
                return Html::a($cardPenyerahanId, ['card/view', 'id' => $model->card_id], ['title' => $cardPenyerahanId, 'class' => 'modal-btn']);
            }
            return $cardPenyerahanId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'fond_schema_id',
		'value' => $model->fond_schema_id ? $model->fond_schema_id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'schemaTitle',
		'value' => function ($model) {
            $schemaTitle = isset($model->schema) ? $model->schema->title : '-';
            if ($schemaTitle != '-') {
                return Html::a($schemaTitle, ['schema/view', 'id' => $model->schema_id], ['title' => $schemaTitle, 'class' => 'modal-btn']);
            }
            return $schemaTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'finalFondName',
		'value' => function ($model) {
            $finalFondName = isset($model->final) ? $model->final->fond_name : '-';
            if ($finalFondName != '-') {
                return Html::a($finalFondName, ['final/view', 'id' => $model->final_id], ['title' => $finalFondName, 'class' => 'modal-btn']);
            }
            return $finalFondName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'fondTitle',
		'value' => function ($model) {
            $fondTitle = isset($model->fond) ? $model->fond->title : '-';
            if ($fondTitle != '-') {
                return Html::a($fondTitle, ['fond/view', 'id' => $model->fond_id], ['title' => $fondTitle, 'class' => 'modal-btn']);
            }
            return $fondTitle;
		},
		'format' => 'html',
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