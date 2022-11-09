<?php
/**
 * CardController
 * @var $this ommu\archivePengolahan\controllers\schema\CardController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanSchemaCard
 *
 * CardController implements the CRUD actions for ArchivePengolahanSchemaCard model.
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
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\schema;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanSchemaCard;
use ommu\archivePengolahan\models\search\ArchivePengolahanSchemaCard as ArchivePengolahanSchemaCardSearch;

class CardController extends Controller
{
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
	 * Lists all ArchivePengolahanSchemaCard models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanSchemaCardSearch();
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

        if (($schema = Yii::$app->request->get('schema')) != null) {
            $schema = \ommu\uuid\models\ArchivePengolahanSchema::findOne($schema);
        }
        if (($card = Yii::$app->request->get('card')) != null) {
            $card = \ommu\uuid\models\ArchivePengolahanPenyerahanCard::findOne($card);
        }
        if (($fond = Yii::$app->request->get('fond')) != null) {
            $fond = \ommu\archivePengolahan\models\Archives::findOne($fond);
        }
        if (($archive = Yii::$app->request->get('archive')) != null) {
            $archive = \ommu\archivePengolahan\models\Archives::findOne($archive);
        }
        if (($final = Yii::$app->request->get('final')) != null) {
            $final = \ommu\archivePengolahan\models\ArchivePengolahanFinal::findOne($final);
        }

		$this->view->title = Yii::t('app', 'Manuver Kartu');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'schema' => $schema,
			'card' => $card,
			'fond' => $fond,
			'archive' => $archive,
			'final' => $final,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanSchemaCard model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchivePengolahanSchemaCard();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Manuver Kartu');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanSchemaCard model.
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success updated.'));
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

		$this->view->title = Yii::t('app', 'Update Manuver Kartu: {card-id}', ['card-id' => $model->card->penyerahan->type->type_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanSchemaCard model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Manuver Kartu: {card-id}', ['card-id' => $model->card->penyerahan->type->type_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanSchemaCard model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * actionPublish an existing ArchivePengolahanSchemaCard model.
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
            Yii::$app->session->setFlash('success', Yii::t('app', 'Manuver kartu success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * Finds the ArchivePengolahanSchemaCard model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchivePengolahanSchemaCard the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanSchemaCard::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}