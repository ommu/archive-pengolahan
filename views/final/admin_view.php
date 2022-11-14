<?php
/**
 * Archive Pengolahan Finals (archive-pengolahan-final)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\FinalController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanFinal
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 13 November 2022, 12:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finalisasi'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->fond_name;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-pengolahan-final-view">

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
		'attribute' => 'fond_number',
		'value' => $model->fond_number ? $model->fond_number : '-',
	],
	[
		'attribute' => 'fond_name',
		'value' => $model->fond_name ? $model->fond_name : '-',
	],
	[
		'attribute' => 'archive_start_from',
		'value' => $model->archive_start_from ? $model->archive_start_from : '-',
		'visible' => !$small,
	],
    [
        'attribute' => 'oCard',
        'value' => function ($model) {
            return $model->getCards(true); 
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
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
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