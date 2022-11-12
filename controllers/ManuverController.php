<?php
/**
 * ManuverController
 * @var $this ommu\archivePengolahan\controllers\ManuverController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchema
 *
 * ManuverController implements the CRUD actions for ArchivePengolahanSchema model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Card
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 23:35 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanSchema;
use ommu\archivePengolahan\models\search\ArchivePengolahanSchema as ArchivePengolahanSchemaSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;
use yii\helpers\ArrayHelper;

class ManuverController extends Controller
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
        $searchModel = new ArchivePengolahanSchemaSearch(['isManuver' => true, 'publish' => 1]);
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

		$this->view->title = Yii::t('app', 'Manuver Kartu');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanSchema model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCard($id)
	{
		$model = $this->findModel($id);
        $referenceCode = $model->referenceCode;
        $fondId = array_key_first($model->referenceCode);
        $title = $model->title;
        $code = $model->code;
        if (!$model->isFond) {
            $code = implode('.', ArrayHelper::map($referenceCode, 'id', 'code'));
        }
        $schemaTitle = join(' ', [$code, $title]);

		$this->layout = 'admin_default';
		$this->view->title = Yii::t('app', 'Manuver: {schema}', ['schema' => $schemaTitle]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_card', [
			'model' => $model,
			'fondId' => $fondId,
		]);
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