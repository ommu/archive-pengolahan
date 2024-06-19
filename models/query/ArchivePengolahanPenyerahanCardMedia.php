<?php
/**
 * ArchivePengolahanPenyerahanCardMedia
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardMedia]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardMedia
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 20:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanPenyerahanCardMedia extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardMedia[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardMedia|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
