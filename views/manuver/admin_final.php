<?php
/**
 * Archive Pengolahan Finals (archive-pengolahan-final)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\FinalController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanFinal
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 13 November 2022, 12:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengolahan Finals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-final-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
