<?php
/**
 * GroupController
 * @var $this ommu\archivePengolahan\controllers\user\GroupController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanUserGroup
 *
 * GroupController implements the CRUD actions for ArchivePengolahanUserGroup model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 08:47 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\user;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanUserGroup;
use ommu\archivePengolahan\models\search\ArchivePengolahanUserGroup as ArchivePengolahanUserGroupSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class GroupController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
					'publish' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all ArchivePengolahanUserGroup models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanUserGroupSearch();
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

		$this->view->title = Yii::t('app', 'User Groups');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanUserGroup model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchivePengolahanUserGroup();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive user group success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create User Group');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanUserGroup model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive user group success updated.'));
                if ($model->stayInHere) {
                    return $this->redirect(['update', 'id' => $model->id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update User Group: {name}', ['name' => $model->name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanUserGroup model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail User Group: {name}', ['name' => $model->name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanUserGroup model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive user group success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanUserGroup model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive user group success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * Finds the ArchivePengolahanUserGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanUserGroup the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanUserGroup::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}