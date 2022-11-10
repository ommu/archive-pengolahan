<?php
/**
 * m221110_100906_archivePengolahan_module_addTrigger_afterDelete_item_card
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 November 2022, 08:06 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m221110_100906_archivePengolahan_module_addTrigger_afterDelete_item_card extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanCard`');

        // create trigger archivePengolahanAfterDeletePenyerahanItem
        $archivePengolahanAfterDeletePenyerahanItem = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterDeletePenyerahanItem` AFTER DELETE ON `ommu_archive_pengolahan_penyerahan_item` 
    FOR EACH ROW BEGIN
	IF (OLD.publish <> 2) THEN
		UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `item` = `item` - 1 WHERE `id` = OLD.penyerahan_id;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterDeletePenyerahanItem);

        // create trigger archivePengolahanAfterDeletePenyerahanCard
        $archivePengolahanAfterDeletePenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterDeletePenyerahanCard` AFTER DELETE ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	IF (OLD.publish <> 2) THEN
		UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` - 1 WHERE `id` = OLD.penyerahan_id;
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanAfterDeletePenyerahanCard);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanItem`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterDeletePenyerahanCard`');
    }
}
