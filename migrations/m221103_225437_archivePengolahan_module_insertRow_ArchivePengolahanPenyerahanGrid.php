<?php
/**
 * m221103_225437_archivePengolahan_module_insertRow_ArchivePengolahanPenyerahanGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 22:55 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_225437_archivePengolahan_module_insertRow_ArchivePengolahanPenyerahanGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchivePengolahanPenyerahanGrid = <<< SQL
INSERT INTO `ommu_archive_pengolahan_penyerahan_grid` (`id`, `card`, `item`, `jenis`) 

SELECT 
	a.id AS id,
	case when a.cards is null then 0 else a.cards end AS `cards`,
	case when a.items is null then 0 else a.items end AS `items`,
	case when a.jenis is null then 0 else a.jenis end AS `jenis`
FROM _archive_pengolahan_penyerahan AS a
LEFT JOIN ommu_archive_pengolahan_penyerahan_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchivePengolahanPenyerahanGrid);
	}
}
