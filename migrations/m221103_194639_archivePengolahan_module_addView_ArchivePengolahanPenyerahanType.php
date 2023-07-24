<?php
/**
 * m221103_194639_archivePengolahan_module_addView_ArchivePengolahanPenyerahanType
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 19:47 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_194639_archivePengolahan_module_addView_ArchivePengolahanPenyerahanType extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_type`');

		// add view _archive_pengolahan_penyerahan_type
		$addViewArchiveLevelStatisticArchive = <<< SQL
CREATE VIEW `_archive_pengolahan_penyerahan_type` AS
select
  `a`.`id` AS `id`,
  count(`b`.`id`) AS `penyerahan`
from (`ommu_archive_pengolahan_penyerahan_type` `a`
   left join `ommu_archive_pengolahan_penyerahan` `b`
     on (`a`.`id` = `b`.`type_id`
         and `b`.`publish` <> 2))
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveLevelStatisticArchive);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_type`');
    }
}
