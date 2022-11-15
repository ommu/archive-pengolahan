<?php
/**
 * m221106_114535_archivePengolahan_module_insert_menu
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

class m221106_114535_archivePengolahan_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['SIKS (Pengolahan)', 'archive-pengolahan', 'fa-archive', null, '/#', null, null],
			]);

			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Penyerahan', 'archive-pengolahan', null, Menu::getParentId('SIKS (Pengolahan)#archive-pengolahan'), '/archive-pengolahan/penyerahan/admin/index', 1, null],
				['Senarai Luring', 'archive-pengolahan', null, Menu::getParentId('SIKS (Pengolahan)#archive-pengolahan'), '/archive-pengolahan/luring/admin/index', 2, null],
				['Archive Locations', 'archive-pengolahan', null, Menu::getParentId('SIKS (Pengolahan)#archive-pengolahan'), '/archive-pengolahan/location/index', 3, null],
				['Archive Settings', 'archive-pengolahan', null, Menu::getParentId('SIKS (Pengolahan)#archive-pengolahan'), '/#', 4, null],
			]);

			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Tipe Penyerahan', 'archive-pengolahan', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-pengolahan/setting/type/index', 1, null],
				['Jenis Arsip', 'archive-pengolahan', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-pengolahan/setting/jenis/index', 2, null],
				['Physical Storage', 'archive-location', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-location/admin/index', 3, null],
				['User Group', 'archive-pengolahan', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-pengolahan/user/group/index', 4, null],
				['Users', 'archive-pengolahan', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-pengolahan/user/admin/index', 5, null],
				['Archive Settings', 'archive-pengolahan', null, Menu::getParentId('Archive Settings#archive-pengolahan'), '/archive-pengolahan/setting/admin/update', 6, null],
			]);
		}
	}
}
