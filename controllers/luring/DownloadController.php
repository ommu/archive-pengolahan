<?php
/**
 * DownloadController
 * @var $this ommu\archivePengolahan\controllers\luring\DownloadController
 * @var $model ommu\archive\models\ArchiveLurings
 *
 * DownloadController implements the CRUD actions for ArchiveLuringDownload model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 October 2022, 08:13 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\luring;

use Yii;
use ommu\archive\controllers\luring\DownloadController as ArchiveDownloadController;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class DownloadController extends ArchiveDownloadController
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        if (Yii::$app->request->get('archive') || Yii::$app->request->get('id')) {
            if (array_key_exists('luring_submenu', $this->module->params)) {
                $this->subMenu = $this->module->params['luring_submenu'];
            }
        }

        $setting = new ArchivePengolahanSetting(['app' => 'archivePengolahanModule']);

        parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function ignoreLevelField()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPengolahan()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return Yii::getAlias('@ommu/archive/views/luring/download');
	}
}
