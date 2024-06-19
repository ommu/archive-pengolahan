<?php
/**
 * m221111_231723_archivePengolahan_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 23:18 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m221111_231723_archivePengolahan_module_insert_role extends \yii\db\Migration
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
				['/archive-pengolahan/penyerahan/card/*', '2', '', time()],
				['/archive-pengolahan/penyerahan/card/index', '2', '', time()],
				['/archive-pengolahan/schema/admin/*', '2', '', time()],
				['/archive-pengolahan/schema/admin/index', '2', '', time()],
				['/archive-pengolahan/schema/sync/*', '2', '', time()],
				['/archive-pengolahan/schema/sync/index', '2', '', time()],
				['/archive-pengolahan/manuver/*', '2', '', time()],
				['/archive-pengolahan/manuver/index', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['archivePengolahan', '/archive-pengolahan/penyerahan/card/*'],
				['archiveArsiparis', '/archive-pengolahan/schema/admin/*'],
				['archiveArsiparis', '/archive-pengolahan/schema/sync/*'],
				['archiveArsiparis', '/archive-pengolahan/manuver/*'],
			]);
		}
	}
}
