<?php
/**
 * m221108_194217_archive_module_addColumn_syncSchema_archives
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 19:43 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221108_194217_archive_module_addColumn_syncSchema_archives extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'sync_schema',
				$this->tinyInteger(1)
                    ->notNull()
                    ->defaultValue(0)
                    ->after('senarai_file'),
			);
		}
	}
}
