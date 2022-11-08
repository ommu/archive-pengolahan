<?php
/**
 * m221108_220600_archivePengolahan_module_addTrigger_allBeforeUpdate
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 15:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use Yii;
use yii\db\Schema;

class m221108_220600_archivePengolahan_module_addTrigger_allBeforeUpdate extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateSchema`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateSchemaCard`');

        // create trigger archivePengolahanBeforeUpdateSchema
        $archivePengolahanBeforeUpdateSchema = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdateSchema` BEFORE UPDATE ON `ommu_archive_pengolahan_schema` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdateSchema);

        // create trigger archivePengolahanBeforeUpdateSchemaCard
        $archivePengolahanBeforeUpdateSchemaCard = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdateSchemaCard` BEFORE UPDATE ON `ommu_archive_pengolahan_schema_card` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdateSchemaCard);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateSchema`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateSchemaCard`');
    }
}
