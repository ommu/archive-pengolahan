<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\schema\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:12 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schema'), 'url' => ['index']];
if ($parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent::htmlHardDecode($parent->title), 'url' => ['view', 'id' => $parent->id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Childs'), 'url' => ['manage', 'parent' => $parent->id]];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-schema-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'parent' => $parent,
]); ?>

</div>
