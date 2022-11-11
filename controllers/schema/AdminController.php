<?php
/**
 * AdminController
 * @var $this ommu\archivePengolahan\controllers\schema\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 *
 * AdminController implements the CRUD actions for ArchivePengolahanSchema model.
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
 * @created date 8 November 2022, 22:12 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\schema;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanSchema;
use ommu\archivePengolahan\models\search\ArchivePengolahanSchema as ArchivePengolahanSchemaSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('parent')) {
            $this->subMenu = $this->module->params['schemma_submenu'];
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
	 * Lists all ArchivePengolahanSchema models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanSchemaSearch();
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

        if (($parent = Yii::$app->request->get('parent')) != null) {
            $this->subMenuParam = $parent;
            $parent = \ommu\archivePengolahan\models\ArchivePengolahanSchema::findOne($parent);
            if (!$parent->isFond) {
                unset($this->subMenu[1]['tree']);
            }
        }

		$this->view->title = Yii::t('app', 'Schemas');
        if ($parent) {
            $this->view->title = Yii::t('app', 'Childs');
        }
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'parent' => $parent,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanSchema model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchivePengolahanSchema();
        if (($id = Yii::$app->request->get('id')) != null) {
			$model = new ArchivePengolahanSchema(['parent_id' => $id]);
        }
        $parent = $model->parent;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Schema success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'id' => $model->parent_id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        if ($parent && !$parent->isFond) {
            unset($this->subMenu[1]['tree']);
        }
		$this->view->title = Yii::t('app', 'Create Schema');
        if ($parent) {
            $this->view->title = Yii::t('app', 'Create Child');
        }
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'parent' => $parent ?? null,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanSchema model.
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Schema success updated.'));
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

        if (!$model->isFond) {
            unset($this->subMenu[1]['tree']);
        }
		$this->view->title = Yii::t('app', 'Update Schema: {title}', ['title' => $model::htmlHardDecode($model->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanSchema model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (!$model->isFond) {
            unset($this->subMenu[1]['tree']);
        }
		$this->view->title = Yii::t('app', 'Detail Schema: {title}', ['title' => $model::htmlHardDecode($model->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanSchema model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Schema success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'parent' => $model->parent_id]);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanSchema model.
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
            Yii::$app->session->setFlash('success', Yii::t('app', 'Schema success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'parent' => $model->parent_id]);
        }
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionTree($id)
	{
		$model = $this->findModel($id);
        $sync = Yii::$app->request->get('sync');

		$this->view->title = Yii::t('app', 'Tree Schema');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_tree', [
			'model' => $model,
			'sync' => $sync ?? null,
		]);
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionData($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = ArchivePengolahanSchema::findOne($id);

        if ($model == null) return [];

		$codes = [];
		$result[] = $this->getData($model, $codes);

		return $result;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionManuver($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = ArchivePengolahanSchema::findOne($id);

        if ($model == null) return [];

		$codes = [];
		$result[] = $this->getManuver($model);

		return $result;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getData($model, $codes)
	{
		$data = [
			'id' => $model->id,
			'code' => $model->code,
			'label' => $model::htmlHardDecode($model->title),
			'inode' => $model->getChilds(true, 1) ? true : false,
			'manuver' => false,
			'menuver-url' => false,
			'view-url' => Url::to(['view', 'id' => $model->id]),
			'update-url' => Url::to(['update', 'id' => $model->id]),
			'child-url' => Url::to(['manage', 'parent' => $model->id]),
		];
        if (!empty($codes)) {
			$data = ArrayHelper::merge($data, ['open' => true, 'branch' => [$codes]]);
        }
		
        if (isset($model->parent)) {
			$data = $this->getData($model->parent, $data);
        }

		return $data;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getManuver($model, $manuver=false)
	{
		$data = [
			'id' => $model->id,
			'code' => $model->code,
			'label' => $model::htmlHardDecode($model->title),
			'inode' => $model->getChilds(true, 1) ? true : false,
			'manuver' => $manuver,
			'menuver-url' => $manuver ? Url::to(['view', 'id' => $model->id]) : false,
			'view-url' => $manuver ? false : Url::to(['view', 'id' => $model->id]),
			'update-url' => $manuver ? false : Url::to(['update', 'id' => $model->id]),
			'child-url' => $manuver ? false : Url::to(['manage', 'parent' => $model->id]),
		];

        $childs = $model->getChilds()
            ->select(['id', 'parent_id', 'code', 'title'])
            ->orderBy('code ASC')
            ->all();

        if ($childs) {
            $cards = [];
            $i = 0;
            foreach ($childs as $child) {
                $cards[$i] = $this->getManuver($child);
                $i++;
            }
            $data = ArrayHelper::merge($data, ['open' => true, 'branch' => $cards]);
        }

		return $data;
    }

	/**
	 * Finds the ArchivePengolahanSchema model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchivePengolahanSchema the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanSchema::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}