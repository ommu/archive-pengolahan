<?php
/**
 * ArchivePengolahanSchemaCard
 *
 * This is the ActiveQuery class for [[\ommu\archivePengolahan\models\ArchivePengolahanSchemaCard]].
 * @see \ommu\archivePengolahan\models\ArchivePengolahanSchemaCard
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\query;

class ArchivePengolahanSchemaCard extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanSchemaCard[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\ArchivePengolahanSchemaCard|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
