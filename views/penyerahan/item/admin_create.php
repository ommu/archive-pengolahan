<?php
/**
 * Archive Pengolahan Penyerahan Items (archive-pengolahan-penyerahan-item)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\ItemController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:19 WIB
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
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item'), 'url' => ['manage', 'penyerahan' => $penyerahan->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan Items'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-penyerahan-item-create">

<?php echo $this->render('_form', [
	'model' => $model,
    'penyerahan' => $penyerahan,
]); ?>

</div>
