<?php
/**
 * Archive Pengolahan Penyerahan Cards (archive-pengolahan-penyerahan-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 11:25 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['penyerahan/admin/index']];
$this->params['breadcrumbs'][] = ['label' => $model->type->type_name. ': ' .$model->penyerahan->kode_box, 'url' => ['penyerahan/admin/view', 'id' => $model->penyerahan_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Card'), 'url' => ['manage', 'penyerahan' => $model->penyerahan_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="archive-pengolahan-penyerahan-card-update">

<?php echo $this->render('_form', [
	'model' => $model,
    'penyerahan' => $model->penyerahan,
    'user' => $model->user,
]); ?>

</div>