<?php
/**
 * ArchivePengolahanPenyerahanCreator
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCreator]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCreator
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 07:54 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanPenyerahanCreator extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCreator[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCreator|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
