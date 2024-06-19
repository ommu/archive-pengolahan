<?php
/**
 * m221108_153325_archivePengolahan_module_addTrigger_afterUpdate_card
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 15:34 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221108_153325_archivePengolahan_module_addTrigger_afterUpdate_card extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanCard`');

        // create trigger archivePengolahanAfterInsertPenyerahanCard
        $archivePengolahanAfterInsertPenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanCard` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` + 1 WHERE `id` = NEW.penyerahan_id;
	UPDATE `ommu_archive_pengolahan_users` SET `archives` = `archives` + 1 WHERE `id` = NEW.user_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanCard);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanCard`');

        // create trigger archivePengolahanAfterInsertPenyerahanCard
        $archivePengolahanAfterInsertPenyerahanCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanCard` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_card` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_grid` SET `card` = `card` + 1 WHERE `id` = NEW.penyerahan_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanCard);
    }
}
