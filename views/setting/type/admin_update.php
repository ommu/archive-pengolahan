<?php
/**
 * Archive Pengolahan Penyerahan Types (archive-pengolahan-penyerahan-type)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\setting\TypeController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 07:52 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="archive-pengolahan-penyerahan-type-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>