<?php
/**
 * m230724_115748_archivePengolahanModule_addColumn_penyerahanCard_archiveDateToTime
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 11:59 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

class m230724_115748_archivePengolahanModule_addColumn_penyerahanCard_archiveDateToTime extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_penyerahan_card';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'to_archive_date_totime',
				$this->integer(11)
                    ->notNull()
                    ->after('to_archive_date'),
			);
			$this->addColumn(
				$tableName,
				'from_archive_date_totime',
				$this->integer(11)
                    ->notNull()
                    ->after('to_archive_date'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_penyerahan_card';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'to_archive_date_totime',
			);
			$this->dropColumn(
				$tableName,
				'from_archive_date_totime',
			);
		}
	}
}
