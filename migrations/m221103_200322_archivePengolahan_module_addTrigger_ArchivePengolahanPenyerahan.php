<?php
/**
 * m221103_200322_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahan
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 20:04 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_200322_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahan extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahan`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahan`');

        // create trigger archivePengolahanAfterInsertPenyerahan
        $archivePengolahanAfterInsertPenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahan` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` + 1 WHERE `id` = NEW.type_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahan);

        // create trigger archivePengolahanAfterUpdatePenyerahan
        $archivePengolahanAfterUpdatePenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterUpdatePenyerahan` AFTER UPDATE ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` - 1 WHERE `id` = NEW.type_id;
		ELSEIF (OLD.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` + 1 WHERE `id` = NEW.type_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterUpdatePenyerahan);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahan`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahan`');
    }
}
