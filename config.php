<?php
/**
 * archive-pengolahan module config
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 05:55 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use ommu\archivePengolahan\Events;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard;

return [
	'id' => 'archive-pengolahan',
	'class' => ommu\archivePengolahan\Module::className(),
	'events' => [
		[
			'class'    => ArchivePengolahanPenyerahan::className(),
			'event'    => ArchivePengolahanPenyerahan::EVENT_BEFORE_SAVE_PENYERAHAN,
			'callback' => [Events::className(), 'onBeforeSavePenyerahan']
		],
		[
			'class'    => ArchivePengolahanPenyerahanCard::className(),
			'event'    => ArchivePengolahanPenyerahanCard::EVENT_BEFORE_SAVE_PENYERAHAN_CARD,
			'callback' => [Events::className(), 'onBeforeSavePenyerahanCard']
        ],
	],
];