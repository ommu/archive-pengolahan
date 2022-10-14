<?php
/**
 * ItemController
 * @var $this ommu\archivePengolahan\controllers\penyerahan\ItemController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem
 *
 * ItemController implements the CRUD actions for ArchivePengolahanPenyerahanItem model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:18 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\penyerahan;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanItem as ArchivePengolahanPenyerahanItemSearch;

class ItemController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('penyerahan')) {
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
	 * Lists all ArchivePengolahanPenyerahanItem models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanPenyerahanItemSearch();
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

        if (($penyerahan = Yii::$app->request->get('penyerahan')) != null) {
            $this->subMenuParam = $penyerahan;
            $penyerahan = \ommu\archivePengolahan\models\ArchivePengolahanPenyerahan::findOne($penyerahan);
        }

		$this->view->title = Yii::t('app', 'Penyerahan Items');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'penyerahan' => $penyerahan,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanPenyerahanItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (!($id = Yii::$app->request->get('id'))) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $penyerahan = \ommu\archivePengolahan\models\ArchivePengolahanPenyerahan::findOne($id);
        $model = new ArchivePengolahanPenyerahanItem(['penyerahan_id' => $id]);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan item success created.'));
                return $this->redirect(['manage', 'penyerahan' => $model->penyerahan_id]);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $this->subMenuParam = $id;
		$this->view->title = Yii::t('app', 'Create Penyerahan Item');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'penyerahan' => $penyerahan,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahanItem model.
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan item success updated.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $this->subMenuParam = $model->penyerahan_id;
		$this->view->title = Yii::t('app', 'Update Penyerahan Item: {penyerahan-id}', ['penyerahan-id' => $model->penyerahan->type->type_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanPenyerahanItem model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        $this->subMenuParam = $model->penyerahan_id;
		$this->view->title = Yii::t('app', 'Detail Penyerahan Item: {penyerahan-id}', ['penyerahan-id' => $model->penyerahan->type->type_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanPenyerahanItem model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan item success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'penyerahan' => $model->penyerahan_id]);
        }
	}

	/**
	 * Finds the ArchivePengolahanPenyerahanItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanPenyerahanItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanPenyerahanItem::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}