<?php
/**
 * Archive Pengolahan User Groups (archive-pengolahan-user-group)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\user\GroupController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanUserGroup
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 08:47 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['setting/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-pengolahan-user-group-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
