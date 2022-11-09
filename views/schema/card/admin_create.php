<?php
/**
 * Archive Pengolahan Schema Cards (archive-pengolahan-schema-card)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchemaCard
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manuver Kartu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-schema-card-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
