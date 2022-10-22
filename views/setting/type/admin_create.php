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
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-penyerahan-type-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
