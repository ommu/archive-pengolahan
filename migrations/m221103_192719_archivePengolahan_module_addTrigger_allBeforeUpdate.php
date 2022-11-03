<?php
/**
 * m221103_192719_archivePengolahan_module_addTrigger_allBeforeUpdate
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 19:28 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221103_192719_archivePengolahan_module_addTrigger_allBeforeUpdate extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanType`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahan`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanCard`');

        // create trigger archivePengolahanBeforeUpdatePenyerahanType
        $archivePengolahanBeforeUpdatePenyerahanType = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdatePenyerahanType` BEFORE UPDATE ON `ommu_archive_pengolahan_penyerahan_type` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdatePenyerahanType);

        // create trigger archivePengolahanBeforeUpdatePenyerahan
        $archivePengolahanBeforeUpdatePenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdatePenyerahan` BEFORE UPDATE ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdatePenyerahan);

        // create trigger archivePengolahanBeforeUpdatePenyerahanItem
        $archivePengolahanBeforeUpdatePenyerahanItem = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdatePenyerahanItem` BEFORE UPDATE ON `ommu_archive_pengolahan_penyerahan_item` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdatePenyerahanItem);

        // create trigger archivePengolahanBeforeUpdatePenyerahanCard
        $archivePengolahanBeforeUpdatePenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdatePenyerahanCard` BEFORE UPDATE ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdatePenyerahanCard);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanType`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahan`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdatePenyerahanCard`');
    }
}
