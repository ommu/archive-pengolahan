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
if ($penyerahan) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['penyerahan/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $penyerahan->type->type_name. ': ' .$penyerahan->kode_box, 'url' => ['penyerahan/admin/view', 'id' => $penyerahan->id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Card'), 'url' => ['manage', 'penyerahan' => $penyerahan->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Description Cards'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-penyerahan-card-create">

<?php echo $this->render('_form', [
	'model' => $model,
    'penyerahan' => $penyerahan,
    'user' => $user,
]); ?>

</div>
