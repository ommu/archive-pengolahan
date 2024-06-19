<?php
/**
 * Archive Pengolahan Schemas (archive-pengolahan-schema)
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\ManuverController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 23:35 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Url;

\ommu\archivePengolahan\components\assets\SchemaTree::register($this);

$treeDataUrl = Url::to(['schema/admin/manuver', 'id' => $fondId, 'action' => 'run']);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>

<div id="tree" class="aciTree"></div>

