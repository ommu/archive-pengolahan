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
 *	Import
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
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\helpers\Inflector;
use thamtech\uuid\helpers\UuidHelper;
use ommu\archivePengolahan\models\ArchivePengolahanImport;

class ItemController extends Controller
{
	use \ommu\traits\FileTrait;

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
			'small' => false,
		]);
	}

	/**
	 * Import a new ArchivePengolahanPenyerahanItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionImport()
	{
        if (($id = Yii::$app->request->get('id')) == null) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $penyerahanAsset = \ommu\archivePengolahan\components\assets\ImportTemplateAsset::register($this->getView());
        $template = join('/', [$penyerahanAsset->baseUrl, 'penyerahanItemImport_template.xlsx']);

		$model = new ArchivePengolahanPenyerahanItem(['penyerahan_id' => $id]);
		$this->subMenuParam = $id;
        $penyerahan = $model->penyerahan;

        if (Yii::$app->request->isPost) {
			$penyerahanPath = $model->penyerahan::getUploadPath();
			$itemImportPath = join('/', [$penyerahanPath, '_import']);
			$verwijderenPath = join('/', [$itemImportPath, 'verwijderen']);
			$this->createUploadDirectory($itemImportPath);

			$errors = [];
			$importFilename = UploadedFile::getInstanceByName('importFilename');
            if ($importFilename instanceof UploadedFile && !$importFilename->getHasError()) {
				$importFileType = ['xlsx', 'xls'];
                if (in_array(strtolower($importFilename->getExtension()), $importFileType)) {
					$fileName = join('_', [Inflector::camelize($model->type->type_name), $model->penyerahan_id, time(), $model->penyerahan->kode_box, 'import', UuidHelper::uuid()]);
					$fileNameExtension = $fileName.'.'.strtolower($importFilename->getExtension());

                    $importId = 0;
                    $import = new ArchivePengolahanImport;
                    $import->type = 'item';
                    $import->original_filename = $importFilename->name;
                    $import->custom_filename = $fileNameExtension;
                    if($import->save()) {
                        $importId = $import->id;
                    }

                    if ($importFilename->saveAs(join('/', [$itemImportPath, $fileNameExtension]))) {
						$spreadsheet = IOFactory::load(join('/', [$itemImportPath, $fileNameExtension]));
						$sheetData = $spreadsheet->getActiveSheet()->toArray();

						try {
                            $i = 0;
                            $j = 0;
							foreach ($sheetData as $key => $value) {
                                if ($key == 0) {
                                    continue;
                                }
                                $i++;
								$archive_number         = trim($value[0]);
								$archive_description    = trim($value[1]);
								$year                   = trim($value[2]);
								$volume                 = trim($value[3]);
								$code                   = trim($value[4]);
								$description            = trim($value[5]);

								$model = new ArchivePengolahanPenyerahanItem;
								$model->penyerahan_id = $id;
								$model->archive_number = $archive_number;
								$model->archive_description = $archive_description;
								$model->year = $year;
								$model->volume = $volume;
								$model->code = $code;
								$model->description = $description;
                                if ($importId) {
								    $model->import_id = $importId;
                                }
                                if (!$model->save()) {
                                    $j++;
                                    $errors['row#'.$key] = $model->getErrors();
                                }
							}
							Yii::$app->session->setFlash('success', Yii::t('app', 'Archive penyerahan item success imported.'));
						} catch (\Exception $e) {
							throw $e;
						} catch (\Throwable $e) {
							throw $e;
						}
					}

                    if ($importId) {
                        $import = ArchivePengolahanImport::findOne($importId);
                        $import->all = $i;
                        $import->error = $j;
                        $import->log = $errors;
                        $import->save();
                    }
	
				} else {
					Yii::$app->session->setFlash('error', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name' => $importFilename->name,
						'extensions' => $importFileType,
					]));
				}
			} else {
				Yii::$app->session->setFlash('error', Yii::t('app', 'Import file cannot be blank.'));
            }

            if (!empty($errors)) {
				$obligationImportErrorFile = join('/', [$itemImportPath, $fileName.'.json']);
                if (!file_exists($obligationImportErrorFile)) {
					file_put_contents($obligationImportErrorFile, Json::encode($errors));
                }
			}

            if (!Yii::$app->request->isAjax) {
				return $this->redirect(['import', 'id' => $id]);
            }
			return $this->redirect(Yii::$app->request->referrer ?: ['import', 'id' => $id]);
		}

		$this->view->title = Yii::t('app', 'Import Penyerahan Item: {penyerahan-id}', ['penyerahan-id' => $model->penyerahan->type->type_name]);
		$this->view->description = '';
        if (Yii::$app->request->isAjax) {
			$this->view->description = Yii::t('app', 'Are you sure you want to import penyerahan item data?');
        }
		$this->view->keywords = '';
		return $this->oRender('admin_import', [
			'model' => $model,
			'template' => $template,
			'penyerahan' => $penyerahan,
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