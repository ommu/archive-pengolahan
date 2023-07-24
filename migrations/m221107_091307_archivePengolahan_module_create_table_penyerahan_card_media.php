<?php
/**
 * m221107_091307_archivePengolahan_module_create_table_penyerahan_card_media
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 09:14 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221107_091307_archivePengolahan_module_create_table_penyerahan_card_media extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_card_media}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'card_id' => Schema::TYPE_STRING . '(36) NOT NULL COMMENT \'uuid\'',
				'media_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_card_media_ibfk_1 FOREIGN KEY ([[card_id]]) REFERENCES {{%ommu_archive_pengolahan_penyerahan_card}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_penyerahan_card_media_ibfk_2 FOREIGN KEY ([[media_id]]) REFERENCES {{%ommu_archive_media}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_penyerahan_card_media}}';
		$this->dropTable($tableName);
	}
}
