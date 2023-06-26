<?php
/**
 * AdminController
 * @var $this ommu\archivePengolahan\controllers\penyerahan\AdminController
 * @var $model ommu\archivePengolahan\models\ArchivePengolahanPenyerahan
 *
 * AdminController implements the CRUD actions for ArchivePengolahanPenyerahan model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  Publication
 *  Status
 *	Import
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers\penyerahan;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan;
use ommu\archivePengolahan\models\search\ArchivePengolahanPenyerahan as ArchivePengolahanPenyerahanSearch;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use thamtech\uuid\helpers\UuidHelper;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType;
use ommu\archivePengolahan\models\ArchivePengolahanImport;
use yii\helpers\Inflector;
use ommu\archivePengolahan\models\ArchivePengolahanSetting;

class AdminController extends Controller
{
	use \ommu\traits\FileTrait;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id')) {
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
	 * Lists all ArchivePengolahanPenyerahan models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchivePengolahanPenyerahanSearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($jenis = Yii::$app->request->get('jenis')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['jenisId' => $jenis]);
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

        if (($type = Yii::$app->request->get('type')) != null) {
            $type = \ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType::findOne($type);
        }
        if (($jenis = Yii::$app->request->get('jenis')) != null) {
            $jenis = \app\models\CoreTags::findOne($jenis);
        }

		$this->view->title = Yii::t('app', 'Penyerahan');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'type' => $type,
			'jenis' => $jenis,
		]);
	}

	/**
	 * Creates a new ArchivePengolahanPenyerahan model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchivePengolahanPenyerahan();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Penyerahan');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahan model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success updated.'));
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

		$this->view->title = Yii::t('app', 'Update Penyerahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchivePengolahanPenyerahan model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        $model->kode_box = nl2br($model->kode_box);
        $model->pencipta_arsip = nl2br($model->pencipta_arsip);
        $model->nomor_arsip = nl2br($model->nomor_arsip);
        $model->jumlah_arsip = nl2br($model->jumlah_arsip);
        $model->nomor_box = nl2br($model->nomor_box);
        $model->jumlah_box = nl2br($model->jumlah_box);
        $model->nomor_box_urutan = nl2br($model->nomor_box_urutan);
        $model->lokasi = nl2br($model->lokasi);
        $model->description = nl2br($model->description);

        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

		$this->view->title = Yii::t('app', 'Detail Penyerahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchivePengolahanPenyerahan model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;
		$model->save(false, ['publish','modified_id']);

		Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahan model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublication($id)
	{
		$model = $this->findModel($id);
        $model->scenario = ArchivePengolahanPenyerahan::SCENARIO_PUBLICATION;
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->publication_file = UploadedFile::getInstance($model, 'publication_file');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success add publication item.'));
                if ($model->stayInHere) {
                    return $this->redirect(['publication', 'id' => $model->id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Upload Publikasi Item Penyerahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_publication', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchivePengolahanPenyerahan model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionStatus($id)
	{
		$model = $this->findModel($id);
        $model->scenario = ArchivePengolahanPenyerahan::SCENARIO_PENGOLAHAN_STATUS;
        if (empty($model->type->feature) || !in_array('item', $model->type->feature)) {
            unset($this->subMenu[1]['item']);
        }
        if (empty($model->type->feature) || !in_array('publication', $model->type->feature)) {
            unset($this->subMenu[1]['publication']);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success updated status pengolahan.'));
                if ($model->stayInHere) {
                    return $this->redirect(['status', 'id' => $model->id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Status Pengolahan: {type-id}', ['type-id' => $model->type->type_name. ' ' .$model->kode_box]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_status', [
			'model' => $model,
		]);
	}

	/**
	 * Import a new ArchivePengolahanPenyerahanItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionImport()
	{
        $penyerahanAsset = \ommu\archivePengolahan\components\assets\ImportTemplateAsset::register($this->getView());
        $template = join('/', [$penyerahanAsset->baseUrl, 'penyerahanImport_template.xlsx']);

		$types = ArchivePengolahanPenyerahanType::find()
			->select(['id', 'type_name'])
			->all();
		$types = ArrayHelper::map($types, 'id', 'type_name');
        if ($types) {
            foreach ($types as $key => $val) {
                $types[$key] = Inflector::camelize(strtolower($val));
            }
        }
		$types = array_flip($types);

        if (Yii::$app->request->isPost) {
			$penyerahanPath = ArchivePengolahanPenyerahan::getUploadPath();
			$penyerahanImportPath = join('/', [$penyerahanPath, '_import']);
			$verwijderenPath = join('/', [$penyerahanImportPath, 'verwijderen']);
			$this->createUploadDirectory($penyerahanImportPath);

			$errors = [];
			$importFilename = UploadedFile::getInstanceByName('importFilename');
            if ($importFilename instanceof UploadedFile && !$importFilename->getHasError()) {
				$importFileType = ['xlsx', 'xls'];
                if (in_array(strtolower($importFilename->getExtension()), $importFileType)) {
					$fileName = join('_', ['penyerahan', time(), 'import', UuidHelper::uuid()]);
					$fileNameExtension = $fileName.'.'.strtolower($importFilename->getExtension());

                    $importId = 0;
                    $import = new ArchivePengolahanImport;
                    $import->type = 'penyerahan';
                    $import->original_filename = $importFilename->name;
                    $import->custom_filename = $fileNameExtension;
                    if($import->save()) {
                        $importId = $import->id;
                    }

                    if ($importFilename->saveAs(join('/', [$penyerahanImportPath, $fileNameExtension]))) {
						$spreadsheet = IOFactory::load(join('/', [$penyerahanImportPath, $fileNameExtension]));
						$sheetData = $spreadsheet->getActiveSheet()->toArray();

						try {
                            $i = 0;
                            $j = 0;
							foreach ($sheetData as $key => $value) {
                                if ($key == 0) {
                                    continue;
                                }
                                $i++;
								$type               = trim($value[0]);
								$kode_box           = trim($value[1]);
								$pencipta_arsip     = trim($value[2]);
								$tahun              = trim($value[3]);
								$nomor_arsip        = trim($value[4]);
								$jumlah_arsip       = trim($value[5]);
								$nomor_box          = trim($value[6]);
								$jumlah_box         = trim($value[7]);
								$nomor_box_urutan   = trim($value[8]);
								$lokasi             = trim($value[9]);
								$color_code         = trim($value[11]);
								$description        = trim($value[12]);
								$jenisArsip         = trim($value[10]);

                                $typeCode = Inflector::camelize(strtolower($type));
                                $typeId = $types[$typeCode];

								$model = new ArchivePengolahanPenyerahan;
								$model->type_id = $typeId;
								$model->kode_box = $kode_box;
								$model->pencipta_arsip = $pencipta_arsip;
								$model->tahun = $tahun;
								$model->nomor_arsip = $nomor_arsip;
								$model->jumlah_arsip = $jumlah_arsip;
								$model->nomor_box = $nomor_box;
								$model->jumlah_box = $jumlah_box;
								$model->nomor_box_urutan = $nomor_box_urutan;
								$model->lokasi = $lokasi;
								$model->color_code = $color_code;
								$model->description = $description;
								$model->jenisArsip = $jenisArsip;
                                if ($importId) {
								    $model->import_id = $importId;
                                }
                                if (!$model->save()) {
                                    $j++;
                                    $errors['row#'.$key] = $model->getErrors();
                                }
							}
							Yii::$app->session->setFlash('success', Yii::t('app', 'Penyerahan success imported.'));
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

            if (!Yii::$app->request->isAjax) {
				return $this->redirect(['import']);
            }
			return $this->redirect(Yii::$app->request->referrer ?: ['import']);
		}

		$this->view->title = Yii::t('app', 'Import Penyerahan');
		$this->view->description = '';
        if (Yii::$app->request->isAjax) {
			$this->view->description = Yii::t('app', 'Are you sure you want to import penyerahan data?');
        }
		$this->view->keywords = '';
		return $this->oRender('admin_import', [
			'template' => $template,
		]);
	}

	/**
	 * Finds the ArchivePengolahanPenyerahan model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchivePengolahanPenyerahan the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchivePengolahanPenyerahan::findOne($id)) !== null) {
            $model->creator = implode(',', $model->getCreators(true, 'title'));
            $model->jenisArsip = implode(',', $model->getJenis(false, 'title'));

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}