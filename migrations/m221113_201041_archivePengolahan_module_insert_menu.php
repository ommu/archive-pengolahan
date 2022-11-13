<?php
/**
 * m221113_201041_archivePengolahan_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 6 November 2022, 11:46 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use mdm\admin\components\Configs;
use app\models\Menu;

class m221113_201041_archivePengolahan_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Finalisasi', 'archive-pengolahan', null, Menu::getParentId('SIKS (Pelestarian)#archive-pengolahan'), '/archive-pengolahan/final/index', null, null],
			]);
		}
	}
}
