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
use ommu\archive\models\ArchiveCreator;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCreator;
use yii\helpers\Inflector;
use app\models\CoreTags;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardMedia;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCardSubject;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSaveArchives($event)
	{
		$penyerahan = $event->sender;

		self::setJenisArsip($penyerahan);
		self::setArchiveCreator($penyerahan);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSavePenyerahan($event)
	{
		$penyerahan = $event->sender;

		self::setJenisArsip($penyerahan);
		self::setArchiveCreator($penyerahan);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSavePenyerahanCard($event)
	{
		$card = $event->sender;

		self::setArchiveMedia($card);
		self::setArchiveSubject($card);
		self::setArchiveSubject($card, 'function');
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setJenisArsip($penyerahan)
	{
        $oldJenisArsip = $penyerahan->getJenis(false, 'title');
        $jenisArsip = [];
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
				$model = ArchivePengolahanPenyerahanJenis::find()
					->select(['id'])
					->where(['penyerahan_id' => $penyerahan->id, 'tag_id' => $key])
					->one();
                $model->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveCreator($penyerahan)
	{
		$oldCreator = $penyerahan->getCreators(true, 'title');
        $creator = [];
        if ($penyerahan->creator) {
            $creator = explode(',', $penyerahan->creator);
        }

		// insert difference creator
        if (is_array($creator)) {
			foreach ($creator as $val) {
                if (in_array($val, $oldCreator)) {
					unset($oldCreator[array_keys($oldCreator, $val)[0]]);
					continue;
				}

				$creatorFind = ArchiveCreator::find()
					->select(['id'])
					->andWhere(['creator_name' => $val])
					->one();

                if ($creatorFind != null) {
                    $creator_id = $creatorFind->id;
                } else {
					$model = new ArchiveCreator();
					$model->creator_name = $val;
                    if ($model->save()) {
                        $creator_id = $model->id;
                    }
				}

				$model = new ArchivePengolahanPenyerahanCreator();
				$model->penyerahan_id = $penyerahan->id;
				$model->creator_id = $creator_id;
				$model->save();
			}
		}

		// drop difference creator
        if (!empty($oldCreator)) {
			foreach ($oldCreator as $key => $val) {
				$model = ArchivePengolahanPenyerahanCreator::find()
					->select(['id'])
					->andWhere(['penyerahan_id' => $penyerahan->id, 'creator_id' => $key])
					->one();
                $model->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveMedia($card)
	{
		$oldMedia = array_flip($card->getMedias(true));
		$media = $card->media;

		// insert difference media
        if (is_array($media)) {
			foreach ($media as $val) {
                if (in_array($val, $oldMedia)) {
					unset($oldMedia[array_keys($oldMedia, $val)[0]]);
					continue;
				}

				$model = new ArchivePengolahanPenyerahanCardMedia();
				$model->card_id = $card->id;
				$model->media_id = $val;
				$model->save();
			}
		}

		// drop difference media
        if (!empty($oldMedia)) {
			foreach ($oldMedia as $key => $val) {
				$model = ArchivePengolahanPenyerahanCardMedia::find()
					->select(['id'])
					->andWhere(['id' => $key])
					->one();
                $model->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveSubject($card, $type='subject')
	{
        $subject = [];
        if ($type == 'subject') {
			$oldSubject = $card->getSubjects(true, 'title');
            if ($card->subject) {
                $subject = explode(',', $card->subject);
            }
		} else {
			$oldSubject = $card->getFunctions(true, 'title');
            if ($card->function) {
                $subject = explode(',', $card->function);
            }
		}

		// insert difference subject
        if (is_array($subject)) {
			foreach ($subject as $val) {
                if (in_array($val, $oldSubject)) {
					unset($oldSubject[array_keys($oldSubject, $val)[0]]);
					continue;
				}

				$model = new ArchivePengolahanPenyerahanCardSubject();
				$model->type = $type;
				$model->card_id = $card->id;
				$model->tagBody = $val;
				$model->save();
			}
		}

		// drop difference subject
        if (!empty($oldSubject)) {
			foreach ($oldSubject as $key => $val) {
				$model = ArchivePengolahanPenyerahanCardSubject::find()
					->select(['id'])
					->where(['type' => $type, 'card_id' => $card->id, 'tag_id' => $key])
					->one();
                $model->delete();
			}
		}
	}
}
