<?php
/**
 * m221103_093550_archivePengolahan_module_create_table_import
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 09:41 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_093550_archivePengolahan_module_create_table_import extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_import}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'type' => Schema::TYPE_STRING . ' NOT NULL',
				'original_filename' => Schema::TYPE_TEXT . ' NOT NULL',
				'custom_filename' => Schema::TYPE_TEXT . ' NOT NULL',
				'all' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'error' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'log' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'json\'',
				'rollback' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_import}}';
		$this->dropTable($tableName);
	}
}
