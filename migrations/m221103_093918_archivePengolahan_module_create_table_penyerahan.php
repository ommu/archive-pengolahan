<?php
/**
 * m221103_093918_archivePengolahan_module_create_table_penyerahan
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 09:39 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_093918_archivePengolahan_module_create_table_penyerahan extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'deleted\'',
				'type_id' => Schema::TYPE_SMALLINT . '(6) UNSIGNED NOT NULL',
				'kode_box' => Schema::TYPE_STRING . '(64) NOT NULL',
				'pencipta_arsip' => Schema::TYPE_TEXT . ' NOT NULL',
				'tahun' => Schema::TYPE_STRING . '(32) NOT NULL',
				'nomor_arsip' => Schema::TYPE_TEXT . ' NOT NULL',
				'jumlah_arsip' => Schema::TYPE_TEXT . ' NOT NULL',
				'nomor_box' => Schema::TYPE_TEXT . ' NOT NULL',
				'jumlah_box' => Schema::TYPE_TEXT . ' NOT NULL',
				'nomor_box_urutan' => Schema::TYPE_TEXT . ' NOT NULL',
				'lokasi' => Schema::TYPE_TEXT . ' NOT NULL',
				'color_code' => Schema::TYPE_STRING . '(32) NOT NULL',
				'description' => Schema::TYPE_TEXT . ' NOT NULL',
				'publication_file' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file,pdf\'',
				'pengolahan_status' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'pengolahan_tahun' => Schema::TYPE_STRING . '(32) NOT NULL',
				'import_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(10) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(10) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_ibfk_1 FOREIGN KEY ([[type_id]]) REFERENCES {{%ommu_archive_pengolahan_penyerahan_type}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_ibfk_2 FOREIGN KEY ([[import_id]]) REFERENCES {{%ommu_archive_pengolahan_import}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan}}';
		$this->dropTable($tableName);
	}
}
