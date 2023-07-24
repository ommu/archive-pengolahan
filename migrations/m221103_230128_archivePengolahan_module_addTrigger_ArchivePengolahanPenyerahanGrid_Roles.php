<?php
/**
 * m221103_230128_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahanGrid_Roles
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 23:01 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_230128_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahanGrid_Roles extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanJenis`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanJenis`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanCard`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahanCard`');

        // create trigger archivePengolahanAfterInsertPenyerahanJenis
        $archivePengolahanAfterInsertPenyerahanJenis = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanJenis` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_jenis` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `jenis` = `jenis` + 1 WHERE `id` = NEW.penyerahan_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanJenis);

        // create trigger archivePengolahanAfterDeletePenyerahanJenis
        $archivePengolahanAfterDeletePenyerahanJenis = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterDeletePenyerahanJenis` AFTER DELETE ON `ommu_archive_pengolahan_penyerahan_jenis` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `jenis` = `jenis` - 1 WHERE `id` = OLD.penyerahan_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterDeletePenyerahanJenis);

        // create trigger archivePengolahanAfterInsertPenyerahanItem
        $archivePengolahanAfterInsertPenyerahanItem = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanItem` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_item` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `item` = `item` + 1 WHERE `id` = NEW.penyerahan_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanItem);

        // create trigger archivePengolahanAfterUpdatePenyerahanItem
        $archivePengolahanAfterUpdatePenyerahanItem = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterUpdatePenyerahanItem` AFTER UPDATE ON `ommu_archive_pengolahan_penyerahan_item` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `item` = `item` - 1 WHERE `id` = NEW.penyerahan_id;
		ELSEIF (OLD.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `item` = `item` + 1 WHERE `id` = NEW.penyerahan_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterUpdatePenyerahanItem);

        // create trigger archivePengolahanAfterInsertPenyerahanCard
        $archivePengolahanAfterInsertPenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanCard` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` + 1 WHERE `id` = NEW.penyerahan_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanCard);

        // create trigger archivePengolahanAfterUpdatePenyerahanCard
        $archivePengolahanAfterUpdatePenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterUpdatePenyerahanCard` AFTER UPDATE ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` - 1 WHERE `id` = NEW.penyerahan_id;
		ELSEIF (OLD.publish = 2) THEN
			UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` + 1 WHERE `id` = NEW.penyerahan_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterUpdatePenyerahanCard);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanJenis`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanJenis`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanCard`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterUpdatePenyerahanCard`');
    }
}
