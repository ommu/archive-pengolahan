<?php
/**
 * Archive Pengolahan Penyerahans (archive-pengolahan-penyerahan)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Penyerahan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type->type_name. ': ' .$model->kode_box, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Status Pengolahan');
?>

<div class="archive-pengolahan-penyerahan-update">

<?php echo $this->render('_form_status', [
	'model' => $model,
]); ?>

</div>
