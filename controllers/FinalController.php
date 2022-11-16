<?php
/**
 * FinalController
 * @var $this ommu\archivePengolahan\controllers\FinalController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanFinal
 *
 * FinalController implements the CRUD actions for ArchivePengolahanFinal model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
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
 * @created date 13 November 2022, 12:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanFinal;
use ommu\archivePengolahan\models\search\ArchivePengolahanFinal as ArchivePengolahanFinalSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSchemaCard;
use yii\helpers\ArrayHelper;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;
use yii\helpers\Json;
use ommu\archive\models\Archives;

class FinalController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('parent')) {
            $this->subMenu = $this->module->params['final_submenu'];
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
	 * Lists all ArchivePengolahanFinal models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanFinalSearch();
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

		$this->view->title = Yii::t('app', 'Finalisasi');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanFinal model.
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Finalisasi success updated.'));
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

        if ($model->publish == 1) {
            unset($this->subMenu[1]['publish']);
            unset($this->subMenu[1]['delete']);
        }
		$this->view->title = Yii::t('app', 'Update Finalisasi: {fond-name}', ['fond-name' => $model->fond_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanFinal model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if ($model->publish == 1) {
            unset($this->subMenu[1]['publish']);
            unset($this->subMenu[1]['delete']);
        }
		$this->view->title = Yii::t('app', 'Detail Finalisasi: {fond-name}', ['fond-name' => $model->fond_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanFinal model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish'])) {
            ArchivePengolahanSchemaCard::updateAll(['final_id' => null], ['final_id' => $id]);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Finalisasi success reset.'));
            return $this->redirect(['manage']);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanFinal model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$model->publish = 1;

        $archives = Json::decode($model->archive_json);
        $archives[$model->fond_schema_id]['code'] = $model->fond_number;
        $archives[$model->fond_schema_id]['label'] = $model->fond_name;
        $startFrom = $model->archive_start_from;

        if ($model->save(false, ['publish'])) {
            if (is_array($archives) && !empty($archives)) {
                foreach ($archives as $archive) {
                    $model = new Archives();
                    $model->level_id = $archive['level_id'];
                    $model->shortCode = $archive['level_id'] == 8 ? strval($startFrom) : $archive['code'];
                    $model->title = $archive['label'];
                    $model->sync_schema = 1;
                    $model->publish = 0;
                    if ($archive['level_id'] == 8) {
                        $manuver = ArchivePengolahanSchemaCard::find()
                            ->select(['id', 'publish', 'card_id'])
                            ->where(['publish' => 1, 'card_id' => $archive['id']])
                            ->one();
                        $card = $manuver->card;
                        $media = array_flip($card->getMedias(true));
                        $subject = implode(',', $card->getSubjects(true, 'title'));
                        $function = implode(',', $card->getFunctions(true, 'title'));
                        $model->medium = $card->medium;
                        $model->archive_date = join(' - ', [$card->from_archive_date, $card->to_archive_date]);
                        $model->archive_type = $card->archive_type ?? null;
                        $model->media = $media;
                        $model->subject =  $subject;
                        $model->function = $function;
                    }
                    if($model->save()) {
                        if ($archive['level_id'] == 8) {
                            ArchivePengolahanSchemaCard::updateAll(['fond_id' => $model->id, 'archive_id' => $model->id], ['id' => $manuver->id]);
                            $startFrom++;
                        }
                        $branchs = $archive['branch'] ?? [];
                        if (!empty($branchs)) {
                            $this->getInsert($branchs, $model->id, $model->id, $startFrom);
                        }
                    }
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Finalisasi success published.'));
            return $this->redirect(['manage']);
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInsert($branchs, $parentId, $fondId, $startFrom)
	{
        foreach ($branchs as $archive) {
            $model = new Archives();
            $model->parent_id = $parentId;
            $model->level_id = $archive['level_id'];
            $model->shortCode = $archive['level_id'] == 8 ? strval($startFrom) : $archive['code'];
            $model->title = $archive['label'];
            $model->sync_schema = 1;
            $model->publish = 0;
            if ($archive['level_id'] == 8) {
				$manuver = ArchivePengolahanSchemaCard::find()
					->select(['id', 'publish', 'card_id'])
					->where(['publish' => 1, 'card_id' => $archive['id']])
					->one();
                $card = $manuver->card;
                $media = array_flip($card->getMedias(true));
                $subject = implode(',', $card->getSubjects(true, 'title'));
                $function = implode(',', $card->getFunctions(true, 'title'));
                $model->medium = $card->medium;
                $model->archive_date = join(' - ', [$card->from_archive_date, $card->to_archive_date]);
                $model->archive_type = $card->archive_type ?? null;
                $model->media = $media;
                $model->subject = $subject;
                $model->function = $function;
            }
            if($model->save()) {
                if ($archive['level_id'] == 8) {
                    ArchivePengolahanSchemaCard::updateAll(['fond_id' => $fondId, 'archive_id' => $model->id], ['id' => $manuver->id]);
                    $startFrom++;
                }
                $branchs = $archive['branch'] ?? [];
                if (!empty($branchs)) {
                    $this->getInsert($branchs, $model->id, $fondId, $startFrom);
                }
            }
        }

        return;
    }

	/**
	 * Displays a single ArchivePengolahanFinal model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionTree($id)
	{
        $model = $this->findModel($id);

        if ($model->publish == 1) {
            unset($this->subMenu[1]['publish']);
            unset($this->subMenu[1]['delete']);
        }
		$this->view->title = Yii::t('app', 'Tree Finalisasi: {fond-name}', ['fond-name' => $model->fond_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_tree', [
			'model' => $model,
		]);
	}

	/**
	 * actionPublish an existing ArchivePengolahanFinal model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionArchive($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = $this->findModel($id);
        $archiveJson = Json::decode($model->archive_json);
        $archiveJson[$model->fond_schema_id]['code'] = $model->fond_number;
        $archiveJson[$model->fond_schema_id]['label'] = $model->fond_name;

        $data = $model->arrayReset($archiveJson);

        return $data;
	}

	/**
	 * Finds the ArchivePengolahanFinal model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanFinal the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanFinal::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}