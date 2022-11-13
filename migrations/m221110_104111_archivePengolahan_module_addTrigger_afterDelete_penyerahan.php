<?php
/**
 * m221110_104111_archivePengolahan_module_addTrigger_afterDelete_penyerahan
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 November 2022, 10:41 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221110_104111_archivePengolahan_module_addTrigger_afterDelete_penyerahan extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahan`');

        // create trigger archivePengolahanAfterDeletePenyerahan
        $archivePengolahanAfterDeletePenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterDeletePenyerahan` AFTER DELETE ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	IF (OLD.publish <> 2) THEN
		UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` - 1 WHERE `id` = OLD.type_id;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterDeletePenyerahan);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahan`');
    }
}
