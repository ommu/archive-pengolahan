<?php
/**
 * SyncController
 * @var $this ommu\archivePengolahan\controllers\schema\SyncController
 * @var $model ommu\archivePengolahan\models\Archives
 *
 * SyncController implements the CRUD actions for Archives model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Run
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 21:46 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\schema;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\Archives;
use ommu\archivePengolahan\models\search\Archives as ArchivesSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;
use yii\helpers\ArrayHelper;
use ommu\archivePengolahan\models\ArchivePengolahanSchema;

class SyncController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

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
                    'run' => ['POST'],
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
	 * Lists all Archives models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivesSearch(['isFond' => true, 'isSchema' => true]);
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

		$this->view->title = Yii::t('app', 'Sync Senarai Schema');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * actionRun an existing Archives model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionRun($id)
	{
		$model = $this->findModel($id);

        $data = [];
        if ($model) {
            $data[$model->id]['id'] = $model->id;
            $data[$model->id]['parent_id'] = $model->parent_id;
            $data[$model->id]['level_id'] = $model->level_id;
            $data[$model->id]['title'] = $model::htmlHardDecode($model->title);
            $data[$model->id]['code'] = $model->code;
            $data[$model->id]['shortCode'] = $model->shortCode;

            $childs = $this->getData($model);
            $data[$model->id] = ArrayHelper::merge($data[$model->id], ['childs' => $childs]);
        }

        if ($data) {
            foreach ($data as $row) {
                $model = new ArchivePengolahanSchema();
                $model->archive_id = $row['id'];
                $model->level_id = $row['level_id'];
                $model->code = $row['shortCode'];
                $model->title = $row['title'];
                if ($model->save()) {
					Archives::updateAll(['sync_schema' => 1, 'fond_schema_id' => $model->id], ['id' => $row['id']]);
                    if (!empty($row['childs'])) {
                        $this->getInsert($row['childs'], $model->id, $model->id);
                    }
                }
            }
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Archive success sync schema.'));
        return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData($archive)
	{
        $childs = $archive->getArchives()
            ->select(['id', 'parent_id', 'level_id', 'title', 'code'])
            ->andWhere(['<>', 't.level_id', 8])
            ->orderBy('code ASC')
            ->all();

        $data = [];
        if ($childs) {
            foreach ($childs as $child) {
                $data[$child->id]['id'] = $child->id;
                $data[$child->id]['parent_id'] = $child->parent_id;
                $data[$child->id]['level_id'] = $child->level_id;
                $data[$child->id]['title'] = $child::htmlHardDecode($child->title);
                $data[$child->id]['code'] = $child->code;
                $data[$child->id]['shortCode'] = $child->shortCode;

                $archives = $this->getData($child);
                $data[$child->id] = ArrayHelper::merge($data[$child->id], ['childs' => $archives]);
            }
        }

        return $data;
    }

	/**
	 * {@inheritdoc}
	 */
	public function getInsert($childs, $parentId, $frontSchemaId)
	{
        foreach ($childs as $row) {
            $model = new ArchivePengolahanSchema();
            $model->parent_id = $parentId;
            $model->archive_id = $row['id'];
            $model->level_id = $row['level_id'];
            $model->code = $row['shortCode'];
            $model->title = $row['title'];
            if ($model->save()) {
                Archives::updateAll(['sync_schema' => 1, 'fond_schema_id' => $frontSchemaId], ['id' => $row['id']]);
                if (!empty($row['childs'])) {
                    $this->getInsert($row['childs'], $model->id, $frontSchemaId);
                }
            }
        }

        return;
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

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}