<?php
/**
 * SchemaTree
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 19:50 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\components\assets;

class SchemaTree extends \yii\web\AssetBundle
{
	public $sourcePath = '@ommu/archive-pengolahan/assets';

	public $js = [
		'js/acitree.js',
	];

	public $depends = [
		'ommu\archive\assets\AciTreePluginAsset',
		'ommu\archive\assets\AciTreeAsset',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
		'except' => [
			'templates',
		],
	];
}