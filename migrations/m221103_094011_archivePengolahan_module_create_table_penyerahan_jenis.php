<?php
/**
 * m221103_094011_archivePengolahan_module_create_table_penyerahan_jenis
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

class m221103_094011_archivePengolahan_module_create_table_penyerahan_jenis extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_jenis}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'penyerahan_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'tag_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_jenis_ibfk_1 FOREIGN KEY ([[penyerahan_id]]) REFERENCES {{%ommu_archive_pengolahan_penyerahan}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_jenis}}';
		$this->dropTable($tableName);
	}
}
