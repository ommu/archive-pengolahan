<?php
/**
 * ArchivePengolahanPenyerahanJenis
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 12 October 2022, 19:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanPenyerahanJenis extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
