<?php
/**
 * ArchivePengolahanPenyerahanCardSubject
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardSubject]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardSubject
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 01:01 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanPenyerahanCardSubject extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardSubject[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardSubject|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
