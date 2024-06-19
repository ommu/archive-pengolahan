<?php
/**
 * m221109_210138_archivePengolahan_module_addTrigger_penyerahan
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 21:02 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221109_210138_archivePengolahan_module_addTrigger_penyerahan extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahan`');

		// alter trigger archivePengolahanAfterInsertPenyerahan
		$archivePengolahanAfterInsertPenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahan` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` + 1 WHERE `id` = NEW.type_id;

	INSERT `ommu_archive_pengolahan_penyerahan_grid` (`id`, `card`, `item`, `jenis`) 
	VALUE (NEW.id, 0, 0, 0);
    END;
SQL;
		$this->execute($archivePengolahanAfterInsertPenyerahan);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahan`');

        // create trigger archivePengolahanAfterInsertPenyerahan
        $archivePengolahanAfterInsertPenyerahan = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahan` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_pengolahan_penyerahan_type_grid` SET `penyerahan` = `penyerahan` + 1 WHERE `id` = NEW.type_id;
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahan);
	}
}
