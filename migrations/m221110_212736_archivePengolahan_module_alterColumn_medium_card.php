<?php
/**
 * m221110_212736_archivePengolahan_module_alterColumn_medium_card
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 November 2022, 21:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221110_212736_archivePengolahan_module_alterColumn_medium_card extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_penyerahan_card';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->alterColumn(
				$tableName,
				'medium',
				$this->string()->notNull(),
			);

			$this->addColumn(
				$tableName,
				'medium_json',
				$this->text()
                    ->notNull()
                    ->after('medium'),
			);

			$this->addCommentOnColumn(
				$tableName,
				'medium_json',
				'json',
			);
		}
	}
}
