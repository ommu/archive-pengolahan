<?php
/**
 * ImportTemplateAsset
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 15 October 2022, 19:09 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\components\assets;

class ImportTemplateAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@ommu/archivePengolahan/assets/templates';

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
	];
}