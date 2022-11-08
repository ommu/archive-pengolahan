<?php
/**
 * m221108_215953_archivePengolahan_module_create_table_schema_card
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:00 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221108_215953_archivePengolahan_module_create_table_schema_card extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_schema_card}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'uuid\'',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'deleted\'',
				'schema_id' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'uuid\'',
				'card_id' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'uuid\'',
				'final_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'fond_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'archive_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(10) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_pengolahan_schema_card_ibfk_1 FOREIGN KEY ([[schema_id]]) REFERENCES {{%ommu_archive_pengolahan_schema}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_schema_card_ibfk_2 FOREIGN KEY ([[card_id]]) REFERENCES {{%ommu_archive_pengolahan_penyerahan_card}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_schema_card_ibfk_4 FOREIGN KEY ([[archive_id]]) REFERENCES {{%ommu_archives}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_pengolahan_schema_card_ibfk_5 FOREIGN KEY ([[final_id]]) REFERENCES {{%ommu_archive_pengolahan_final}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_pengolahan_schema_card}}';
		$this->dropTable($tableName);
	}
}
