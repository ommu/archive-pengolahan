<?php
/**
 * AdminController
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 *
 * AdminController implements the CRUD actions for ArchivePengolahanPenyerahan model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  Status
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\penyerahan;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahan as ArchivePengolahanPenyerahanSearch;
use yii\helpers\ArrayHelper;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id')) {
            $this->subMenu = $this->module->params['penyerahan_submenu'];
        }

		// $setting = ArchiveSetting::find()
		// 	->select(['breadcrumb_param'])
		// 	->where(['id' => 1])
		// 	->one();
		// $this->breadcrumbApp = $setting->breadcrumb;
		// $this->breadcrumbAppParam = $setting->getBreadcrumbAppParam();
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
	 * Lists all ArchivePengolahanPenyerahan models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanPenyerahanSearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($jenis = Yii::$app->request->get('jenis')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['jenisId' => $jenis]);
        }
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

        if (($type = Yii::$app->request->get('type')) != null) {
            $type = \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType::findOne($type);
        }
        if (($jenis = Yii::$app->request->get('jenis')) != null) {
            $jenis = \app\models\CoreTags::findOne($jenis);
        }

		$this->view->title = Yii::t('app', 'Penyerahan');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'type' => $type,
			'jenis' => $jenis,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanPenyerahan model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchivePengolahanPenyerahan();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive Penyerahan success created.'));
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Penyerahan');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahan model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive Penyerahan success updated.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Penyerahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanPenyerahan model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

		$this->view->title = Yii::t('app', 'Detail Penyerahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanPenyerahan model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;
		$model->save(false, ['publish','modified_id']);

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive Penyerahan success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahan model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionStatus($id)
	{
		$model = $this->findModel($id);
        $model->scenario = ArchivePengolahanPenyerahan::SCENARIO_PENGOLAHAN_STATUS;
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive Penyerahan success updated status pengolahan.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Status Pengolahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_status', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the ArchivePengolahanPenyerahan model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanPenyerahan the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanPenyerahan::findOne($id)) !== null) {
            $model->jenisArsip = implode(',', $model->getJenis(false, 'title'));

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}