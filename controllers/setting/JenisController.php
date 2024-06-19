<?php
/**
 * JenisController
 * @var $this ommu\archivePengolahan\controllers\setting\JenisController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis
 *
 * JenisController implements the CRUD actions for ArchivePengolahanPenyerahanJenis model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 12 October 2022, 19:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\setting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanJenis;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanJenis as ArchivePengolahanPenyerahanJenisSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class JenisController extends Controller
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
	 * Lists all ArchivePengolahanPenyerahanJenis models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanPenyerahanJenisSearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($type = Yii::$app->request->get('type')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['penyerahanTypeId' => $type]);
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

        if (($penyerahan = Yii::$app->request->get('penyerahan')) != null) {
            $penyerahan = \ommu\archivePengolahan\models\ArchivePengolahanPenyerahan::findOne($penyerahan);
        }
        if (($tag = Yii::$app->request->get('tag')) != null) {
            $tag = \app\models\CoreTags::findOne($tag);
        }

		$this->view->title = Yii::t('app', 'Jenis Arsip');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'penyerahan' => $penyerahan,
            'tag' => $tag,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanPenyerahanJenis model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Jenis arsip success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionSuggest()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$term = Yii::$app->request->get('term');

        if ($term == null) return [];

		$model = ArchivePengolahanPenyerahanJenis::find()->alias('t')
            ->joinWith(['tag tag'])
			->andWhere(['like', 'tag.body', Inflector::camelize($term)])
			->groupBy(['t.tag_id'])
			->limit(15)
			->all();

		$result = [];
        foreach ($model as $val) {
			$result[] = [
				'id' => $val->tag_id, 
				'label' => $val->tag->body,
			];
		}
		return $result;
	}

	/**
	 * Finds the ArchivePengolahanPenyerahanJenis model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanPenyerahanJenis the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanPenyerahanJenis::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}