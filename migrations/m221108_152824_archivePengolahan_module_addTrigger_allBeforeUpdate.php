<?php
/**
 * m221108_152824_archivePengolahan_module_addTrigger_allBeforeUpdate
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 15:29 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\db\Schema;

class m221108_152824_archivePengolahan_module_addTrigger_allBeforeUpdate extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateUserGroup`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateUsers`');

        // create trigger archivePengolahanBeforeUpdateUserGroup
        $archivePengolahanBeforeUpdateUserGroup = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdateUserGroup` BEFORE UPDATE ON `ommu_archive_pengolahan_user_group` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdateUserGroup);

        // create trigger archivePengolahanBeforeUpdateUsers
        $archivePengolahanBeforeUpdateUsers = <<< SQL
CREATE
    TRIGGER `archivePengolahanBeforeUpdateUsers` BEFORE UPDATE ON `ommu_archive_pengolahan_users` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archivePengolahanBeforeUpdateUsers);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateUserGroup`');
        $this->execute('DROP TRIGGER IF EXISTS `archivePengolahanBeforeUpdateUsers`');
    }
}
