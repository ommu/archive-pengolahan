<?php
/**
 * m221113_201919_archivePengolahan_module_addColumn_levelId_schema
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 November 2022, 21:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221113_201919_archivePengolahan_module_addColumn_levelId_schema extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_schema';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'level_id',
				$this->integer()->unsigned()->after('archive_id'),
			);
		}
	}
}
