<?php
/**
 * AdminController
 * @var $this ommu\archivePengolahan\controllers\luring\AdminController
 * @var $model ommu\archivePengolahan\models\Archives
 *
 * AdminController implements the CRUD actions for Archives model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 17:23 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\luring;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\Archives;
use ommu\archivePengolahan\models\search\Archives as ArchivesSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id')) {
            if ($this->isFond() == true) {
                $this->subMenu = $this->module->params['luring_submenu'];
            } else {
                $this->subMenu = $this->module->params['location_submenu'];
            }
        }

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
	public function isFond()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all Archives models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivesSearch(['isFond' => $this->isFond()]);
        $queryParams = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

		$this->view->title = $this->isFond() ? Yii::t('app', 'Senarai Lurings') : Yii::t('app', 'Archive Locations');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'isFond' => $this->isFond(),
		]);
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {level-name}: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'isFond' => $model->level_id == 1 ? true : false,
			'small' => false,
		]);
	}

	/**
	 * Finds the Archives model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Archives the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = Archives::findOne($id)) !== null) {
            $model->isFond = $this->isFond();
            $model->location = $model->getLocations(false) != null ? 1 : 0;

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}