<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul archive.
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 April 2019, 06:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archivePengolahan;

use Yii;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis;
use yii\helpers\Inflector;
use app\models\CoreTags;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSaveArchives($event)
	{
		$penyerahan = $event->sender;

		self::setJenisArsip($penyerahan);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setJenisArsip($penyerahan)
	{
        $oldJenisArsip = $penyerahan->getJenis(false, 'title');
        if ($penyerahan->jenisArsip) {
            $jenisArsip = explode(',', $penyerahan->jenisArsip);
        }

		// insert difference subject
        if (is_array($jenisArsip)) {
			foreach ($jenisArsip as $val) {
                if (in_array($val, $oldJenisArsip)) {
					unset($oldJenisArsip[array_keys($oldJenisArsip, $val)[0]]);
					continue;
				}

				$model = new ArchivePengolahanPenyerahanJenis();
				$model->penyerahan_id = $penyerahan->id;
				$model->tagBody = $val;
                $model->save();
			}
		}

		// drop difference subject
        if (!empty($oldJenisArsip)) {
			foreach ($oldJenisArsip as $key => $val) {
				ArchivePengolahanPenyerahanJenis::find()
					->select(['id'])
					->where(['penyerahan_id' => $penyerahan->id, 'tag_id' => $key])
					->one()
					->delete();
			}
		}
	}
}
