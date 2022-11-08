<?php
/**
 * m221106_105401_archivePengolahan_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 6 November 2022, 11:25 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m221106_105401_archivePengolahan_module_insert_role extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['archivePengolahanModLevelAdmin', '2', '', time()],
				['archivePengolahanModLevelModerator', '2', '', time()],
				['/archive-pengolahan/setting/admin/*', '2', '', time()],
				['/archive-pengolahan/setting/admin/update', '2', '', time()],
				['/archive-pengolahan/setting/type/*', '2', '', time()],
				['/archive-pengolahan/setting/type/index', '2', '', time()],
				['/archive-pengolahan/setting/jenis/*', '2', '', time()],
				['/archive-pengolahan/setting/jenis/index', '2', '', time()],
				['/archive-pengolahan/user/admin/*', '2', '', time()],
				['/archive-pengolahan/user/admin/index', '2', '', time()],
				['/archive-pengolahan/user/group/*', '2', '', time()],
				['/archive-pengolahan/user/group/index', '2', '', time()],
				['/archive-pengolahan/import/*', '2', '', time()],
				['/archive-pengolahan/import/index', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', 'archivePengolahanModLevelAdmin'],
				['userModerator', 'archivePengolahanModLevelModerator'],
				['archivePengolahanModLevelAdmin', 'archivePengolahanModLevelModerator'],
				['archivePengolahanModLevelAdmin', '/archive-pengolahan/setting/admin/*'],
				['archivePengolahanModLevelAdmin', '/archive-pengolahan/setting/type/*'],
				['archivePengolahanModLevelAdmin', '/archive-pengolahan/user/admin/*'],
				['archivePengolahanModLevelAdmin', '/archive-pengolahan/user/group/*'],
				['archivePengolahanModLevelModerator', '/archive-pengolahan/setting/jenis/*'],
				['archivePengolahanModLevelModerator', '/archive-pengolahan/import/*'],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_pengolahan_user_group';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['publish', 'name', 'permission', 'creation_id'], [
				['1', 'Pelestarian', 'archivePelestarian', Yii::$app->user->id],
				['1', 'Arsiparis', 'archiveArsiparis', Yii::$app->user->id],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['archivePelestarian', '2', '', time()],
				['archiveArsiparis', '2', '', time()],
			]);
		}

		// $tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        // if (Yii::$app->db->getTableSchema($tableName, true)) {
		// 	$this->batchInsert($tableName, ['parent', 'child'], [
		// 		['archivePengolahanModLevelAdmin', 'archivePelestarian'],
		// 		['archivePengolahanModLevelAdmin', 'archiveArsiparis'],
		// 	]);
		// }

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['/archive-pengolahan/location/*', '2', '', time()],
				['/archive-pengolahan/location/index', '2', '', time()],
				['/archive-pengolahan/luring/admin/*', '2', '', time()],
				['/archive-pengolahan/luring/admin/index', '2', '', time()],
				['/archive-pengolahan/luring/document/*', '2', '', time()],
				['/archive-pengolahan/luring/document/index', '2', '', time()],
				['/archive-pengolahan/luring/download/*', '2', '', time()],
				['/archive-pengolahan/luring/download/index', '2', '', time()],
				['/archive-pengolahan/penyerahan/admin/*', '2', '', time()],
				['/archive-pengolahan/penyerahan/admin/index', '2', '', time()],
				['/archive-pengolahan/penyerahan/item/*', '2', '', time()],
				['/archive-pengolahan/penyerahan/item/index', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['archivePelestarian', '/archive-pengolahan/location/*'],
				['archivePelestarian', '/archive-pengolahan/luring/admin/*'],
				['archivePelestarian', '/archive-pengolahan/luring/document/*'],
				['archivePelestarian', '/archive-pengolahan/luring/download/*'],
				['archivePelestarian', '/archive-pengolahan/penyerahan/admin/*'],
				['archivePelestarian', '/archive-pengolahan/penyerahan/item/*'],
			]);
		}
	}
}
