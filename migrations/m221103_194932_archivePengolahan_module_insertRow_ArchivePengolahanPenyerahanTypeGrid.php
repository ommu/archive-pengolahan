<?php
/**
 * m221103_194932_archivePengolahan_module_insertRow_ArchivePengolahanPenyerahanTypeGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 19:51 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221103_194932_archivePengolahan_module_insertRow_ArchivePengolahanPenyerahanTypeGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchivePengolahanPenyerahanTypeGrid = <<< SQL
INSERT INTO `ommu_archive_pengolahan_penyerahan_type_grid` (`id`, `penyerahan`) 

SELECT 
	a.id AS id,
	case when a.penyerahan is null then 0 else a.penyerahan end AS `penyerahan`
FROM _archive_pengolahan_penyerahan_type AS a
LEFT JOIN ommu_archive_pengolahan_penyerahan_type_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchivePengolahanPenyerahanTypeGrid);
	}
}
