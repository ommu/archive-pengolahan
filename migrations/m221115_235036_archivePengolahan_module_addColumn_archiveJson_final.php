<?php
/**
 * m221115_235036_archivePengolahan_module_addColumn_archiveJson_final
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 November 2022, 21:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221115_235036_archivePengolahan_module_addColumn_archiveJson_final extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_final';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'archive_json',
				$this->text()->notNull()->after('fond_schema_id'),
			);

			$this->addCommentOnColumn(
				$tableName,
				'archive_json',
				'json',
			);
		}
	}
}
