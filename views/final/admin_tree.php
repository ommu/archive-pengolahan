<?php
/**
 * Archive Pengolahan Finals (archive-pengolahan-final)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\FinalController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanFinal
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 13 November 2022, 12:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

\ommu\archivePengolahan\components\assets\ArchiveFinalTree::register($this);

$context = $this->context;
if ($context->breadcrumbApp) {
    $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finalisasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->fond_number;

$treeDataUrl = Url::to(['archive', 'id' => $model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>

<div class="archive-pengolahan-final-view">
    <div id="tree" class="aciTree"></div>
</div>