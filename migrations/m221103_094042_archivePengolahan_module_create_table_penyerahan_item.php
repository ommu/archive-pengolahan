<?php
/**
 * m221103_094042_archivePengolahan_module_create_table_penyerahan_item
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 09:40 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221103_094042_archivePengolahan_module_create_table_penyerahan_item extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_item}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'deleted\'',
				'penyerahan_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'archive_number' => Schema::TYPE_STRING . '(16) NOT NULL',
				'archive_description' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'year' => Schema::TYPE_STRING . '(8) NOT NULL',
				'volume' => Schema::TYPE_STRING . '(16) NOT NULL',
				'code' => Schema::TYPE_STRING . '(16) NOT NULL',
				'description' => Schema::TYPE_STRING . '(64) NOT NULL',
				'import_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_item_ibfk_1 FOREIGN KEY ([[penyerahan_id]]) REFERENCES {{%ommu_archive_pengolahan_penyerahan}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_item_ibfk_2 FOREIGN KEY ([[import_id]]) REFERENCES {{%ommu_archive_pengolahan_import}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_item}}';
		$this->dropTable($tableName);
	}
}
