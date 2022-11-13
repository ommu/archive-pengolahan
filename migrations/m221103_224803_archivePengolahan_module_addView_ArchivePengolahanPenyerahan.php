<?php
/**
 * m221103_224803_archivePengolahan_module_addView_ArchivePengolahanPenyerahan
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 22:48 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221103_224803_archivePengolahan_module_addView_ArchivePengolahanPenyerahan extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_jenis`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_item`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_card`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan`');

		// add view _archive_pengolahan_penyerahan_statistic_jenis
		$addViewArchivePengolahanPenyerahanStatisticJenis = <<< SQL
CREATE VIEW `_archive_pengolahan_penyerahan_statistic_jenis` AS
select
  `ommu_archive_pengolahan_penyerahan_jenis`.`penyerahan_id` AS `penyerahan_id`,
  count(`ommu_archive_pengolahan_penyerahan_jenis`.`id`)     AS `jenis`
from `ommu_archive_pengolahan_penyerahan_jenis`
group by `ommu_archive_pengolahan_penyerahan_jenis`.`penyerahan_id`;
SQL;
		$this->execute($addViewArchivePengolahanPenyerahanStatisticJenis);

		// add view _archive_pengolahan_penyerahan_statistic_item
		$addViewArchivePengolahanPenyerahanStatisticItem = <<< SQL
CREATE VIEW `_archive_pengolahan_penyerahan_statistic_item` AS
select
  `ommu_archive_pengolahan_penyerahan_item`.`penyerahan_id` AS `penyerahan_id`,
  count(`ommu_archive_pengolahan_penyerahan_item`.`id`)     AS `items`
from `ommu_archive_pengolahan_penyerahan_item`
where `ommu_archive_pengolahan_penyerahan_item`.`publish` <> 2
group by `ommu_archive_pengolahan_penyerahan_item`.`penyerahan_id`;
SQL;
		$this->execute($addViewArchivePengolahanPenyerahanStatisticItem);

		// add view _archive_pengolahan_penyerahan_statistic_card
		$addViewArchivePengolahanPenyerahanStatisticCard = <<< SQL
CREATE VIEW `_archive_pengolahan_penyerahan_statistic_card` AS
select
  `ommu_archive_pengolahan_penyerahan_card`.`penyerahan_id` AS `penyerahan_id`,
  count(`ommu_archive_pengolahan_penyerahan_card`.`id`)     AS `cards`
from `ommu_archive_pengolahan_penyerahan_card`
where `ommu_archive_pengolahan_penyerahan_card`.`publish` <> 2
group by `ommu_archive_pengolahan_penyerahan_card`.`penyerahan_id`;
SQL;
		$this->execute($addViewArchivePengolahanPenyerahanStatisticCard);

		// add view _archive_pengolahan_penyerahan
		$addViewArchivePengolahanPenyerahan = <<< SQL
CREATE VIEW `_archive_pengolahan_penyerahan` AS
select
  `a`.`id`    AS `id`,
  `b`.`jenis` AS `jenis`,
  `c`.`items` AS `items`,
  `d`.`cards` AS `cards`
from (((`ommu_archive_pengolahan_penyerahan` `a`
     left join `_archive_pengolahan_penyerahan_statistic_jenis` `b`
       on (`b`.`penyerahan_id` = `a`.`id`))
    left join `_archive_pengolahan_penyerahan_statistic_item` `c`
      on (`c`.`penyerahan_id` = `a`.`id`))
   left join `_archive_pengolahan_penyerahan_statistic_card` `d`
     on (`d`.`penyerahan_id` = `a`.`id`));
SQL;
		$this->execute($addViewArchivePengolahanPenyerahan);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_jenis`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_item`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan_statistic_card`');
		$this->execute('DROP VIEW IF EXISTS `_archive_pengolahan_penyerahan`');
    }
}
