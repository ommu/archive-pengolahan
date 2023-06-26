<?php
/**
 * m221103_194935_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahanType
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 June 2023, 14:43 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_194935_archivePengolahan_module_addTrigger_ArchivePengolahanPenyerahanType extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanType`');

        // create trigger archivePengolahanAfterInsertPenyerahanType
        $archivePengolahanAfterInsertPenyerahanType = <<< SQL
CREATE
    TRIGGER `archivePengolahanAfterInsertPenyerahanType` AFTER INSERT ON `ommu_archive_pengolahan_penyerahan_type` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_pengolahan_penyerahan_type_grid` (`id`, `penyerahan`) 
	VALUE (NEW.id, 0);
    END;
SQL;
        $this->execute($archivePengolahanAfterInsertPenyerahanType);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archivePengolahanAfterInsertPenyerahanType`');
    }
}
