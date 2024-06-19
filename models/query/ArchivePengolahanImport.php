<?php
/**
 * ArchivePengolahanImport
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanImport]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanImport
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 21 October 2022, 06:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanImport extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanImport[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanImport|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
