<?php
/**
 * AdminController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Update
 *  Reset
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 29 October 2022, 19:08 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\setting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        $this->subMenu = $this->module->params['setting_submenu'];

        $setting = new ArchivePengolahanSetting(['app' => 'archivePengolahanModule']);
		$this->breadcrumbApp = $setting->breadcrumb;
		$this->breadcrumbAppParam = $setting->getBreadcrumbAppParam();
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return [];
	}

	/**
	 * Index Action
	 */
	public function actionIndex()
	{
        return $this->redirect(['update']);
	}

	/**
	 * Update Action
	 */
	public function actionUpdate()
	{
		$model = new ArchivePengolahanSetting(['app' => 'archivePengolahanModule']);

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Archive setting success updated.'));
				return $this->redirect(['update']);

			} else {
				if (Yii::$app->request->isAjax) {
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->view->title = Yii::t('app', 'Archive Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionReset()
	{
		$model = new ArchivePengolahanSetting(['app' => 'archivePengolahanModule']);
        $model->reset();

        return $this->redirect(['update']);
	}

}
