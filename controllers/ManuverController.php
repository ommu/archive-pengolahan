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
 *  Create
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
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
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahanCard as ArchivePengolahanPenyerahanCardSearch;
use ommu\archivePengolahan\models\ArchivePengolahanSchemaCard;
use thamtech\uuid\helpers\UuidHelper;
use ommu\archivePengolahan\models\ArchivePengolahanFinal;

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
					'create' => ['POST'],
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
	 * actionPublish an existing ArchivePengolahanSchemaCard model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionCreate($id, $schema)
	{
        if (($card = ArchivePengolahanPenyerahanCard::findOne($id)) === null) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $schema = $this->findModel($schema);
        $fondSchemaId = array_key_first($schema->referenceCode);

        $model = new ArchivePengolahanSchemaCard();
        $model->id = UuidHelper::uuid();
		$model->card_id = $card->id;
		$model->fond_schema_id = $fondSchemaId;
		$model->schema_id = $schema->id;

        if ($model->save(false, ['id', 'fond_schema_id', 'card_id', 'schema_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success created.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['card', 'id' => $schema]);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanPenyerahanType model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
        if (($model = ArchivePengolahanSchemaCard::findOne($id)) === null) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['card', 'id' => $model->schema_id]);
        }
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

        $query = ['isMenuver' => true, 'schemaId' => $id];
        if ($fondId == $model->id) {
            $query = ArrayHelper::merge($query, ['isFond' => true]);
        }

        $searchModel = new ArchivePengolahanPenyerahanCardSearch($query);
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
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanFinal model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionFinal($id)
	{
		// Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $model = new ArchivePengolahanFinal();
        $model->fond_schema_id = $id;

        $schema = $this->findModel($id);
        $cardsAllId = $schema->getCards(false, 1, true)
            ->select(['id'])
            ->all();
        $cardsAll = ArchivePengolahanSchemaCard::find()
            ->alias('schemaCard')
            ->select(['schemaCard.id', 'schemaCard.card_id', 'schemaCard.schema_id'])
            ->joinWith([
                'card card', 
            ])
            ->andWhere(['in', 'schemaCard.id', ArrayHelper::map($cardsAllId, 'id', 'id')])
            ->orderBy(['card.from_archive_date_totime' => SORT_ASC])
            ->all();
        $cards = $model->getArchiveCards($cardsAll);

        $schemaFromFinal = $model->getArchive();

        // return $schemaFromFinal;
        // return $cards;
        // return ArrayHelper::merge($schemaFromFinal, $cards);

        // exit();

        $schema = $this->findModel($id);
        $cards = $schema->getCards(false, 1, true)
            ->select(['id'])
            ->all();

        $referenceCode = $schema->referenceCode;
        if (array_key_first($schema->referenceCode) != $id) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $model = new ArchivePengolahanFinal();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->fond_schema_id = $id;
            $model->cardsId = ArrayHelper::map($cards, 'id', 'id');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                ArchivePengolahanSchemaCard::updateAll(['final_id' => $model->id], ['and', ['fond_schema_id' => $id], ['is', 'final_id', null]]);

                Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu final success created.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $title = $schema->title;
        $code = $schema->code;
        if (!$schema->isFond) {
            $code = implode('.', ArrayHelper::map($referenceCode, 'id', 'code'));
        }
        $schemaTitle = join(' ', [$code, $title]);

		$this->view->title = Yii::t('app', 'Manuver Final: {schema}', ['schema' => $schemaTitle]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_final', [
			'model' => $model,
		]);
	}
}