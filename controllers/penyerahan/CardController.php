<?php
/**
 * CardController
 * @var $this ommu\archivePengolahan\controllers\penyerahan\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard
 *
 * CardController implements the CRUD actions for ArchivePengolahanPenyerahanCard model.
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
 * @created date 7 November 2022, 11:25 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\penyerahan;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanCard as ArchivePengolahanPenyerahanCardSearch;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan;
use ommu\archivePengolahan\models\ArchivePengolahanUsers;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class CardController extends Controller
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
	 * Lists all ArchivePengolahanPenyerahanCard models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanPenyerahanCardSearch();
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
            $penyerahan = ArchivePengolahanPenyerahan::findOne($penyerahan);
        }

		$this->view->title = Yii::t('app', 'Description Cards');
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
	 * Creates a new ArchivePengolahanPenyerahanCard model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (!($id = Yii::$app->request->get('id'))) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $penyerahan = ArchivePengolahanPenyerahan::findOne($id);
        $user = ArchivePengolahanUsers::find()
            ->select(['id', 'publish', 'user_id', 'user_code', 'archives'])
            ->andWhere(['in', 'publish', [0,1]])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->one();

        $model = new ArchivePengolahanPenyerahanCard(['penyerahan_id' => $id]);
        if ($user) {
            $model->user_id = $user->id;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan card success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'id' => $model->penyerahan_id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage', 'penyerahan' => $model->penyerahan_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Description Card');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'penyerahan' => $penyerahan,
			'user' => $user,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahanCard model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan card success updated.'));
                if ($model->stayInHere) {
                    return $this->redirect(['update', 'id' => $model->id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage', 'penyerahan' => $model->penyerahan_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $this->subMenuParam = $model->penyerahan_id;
		$this->view->title = Yii::t('app', 'Update Description Card: {penyerahan-tipeId} {penyerahan-kodeBox}', ['penyerahan-tipeId' => $model->type->type_name, 'penyerahan-kodeBox' => $model->penyerahan->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanPenyerahanCard model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        $this->subMenuParam = $model->penyerahan_id;
		$this->view->title = Yii::t('app', 'Detail Description Card: {penyerahan-tipeId} {penyerahan-kodeBox}', ['penyerahan-tipeId' => $model->type->type_name, 'penyerahan-kodeBox' => $model->penyerahan->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanPenyerahanCard model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan card success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'penyerahan' => $model->penyerahan_id]);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanPenyerahanCard model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan card success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'penyerahan' => $model->penyerahan_id]);
        }
	}

	/**
	 * Finds the ArchivePengolahanPenyerahanCard model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchivePengolahanPenyerahanCard the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanPenyerahanCard::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}