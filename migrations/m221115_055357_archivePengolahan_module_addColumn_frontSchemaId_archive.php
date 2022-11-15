<?php
/**
 * m221115_055357_archivePengolahan_module_addColumn_frontSchemaId_archive
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 November 2022, 21:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221115_055357_archivePengolahan_module_addColumn_frontSchemaId_archive extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'fond_schema_id',
				$this->string(36)->notNull()->after('sync_schema'),
			);
		}
	}
}
