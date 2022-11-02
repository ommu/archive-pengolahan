<?php
/**
 * DocumentController
 * @var $this ommu\archivePengolahan\controllers\luring\DocumentController
 * @var $model ommu\archive\models\ArchiveLurings
 *
 * DocumentController implements the CRUD actions for ArchiveLurings model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 October 2022, 00:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\luring;

use Yii;
use ommu\archive\controllers\luring\AdminController;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class DocumentController extends AdminController
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
		return Yii::getAlias('@ommu/archive/views/luring/admin');
	}
}
