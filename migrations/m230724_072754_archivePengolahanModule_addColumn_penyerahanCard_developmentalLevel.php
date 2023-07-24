<?php
/**
 * m230724_072754_archivePengolahanModule_addColumn_penyerahanCard_developmentalLevel
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 07:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

class m230724_072754_archivePengolahanModule_addColumn_penyerahanCard_developmentalLevel extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_penyerahan_card';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'developmental_level',
				$this->string(32)
                    ->notNull()
                    ->after('medium'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_penyerahan_card';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'developmental_level',
			);
		}
	}
}
