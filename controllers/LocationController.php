<?php
/**
 * LocationController
 * @var $this ommu\archivePengolahan\controllers\LocationController
 * @var $model ommu\archivePengolahan\models\Archives
 *
 * LocationController implements the CRUD actions for Archives model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *  Set
 *  Reset
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 23:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers;

use Yii;
use ommu\archivePengolahan\controllers\luring\AdminController;
use ommu\archiveLocation\models\ArchiveLocations;

class LocationController extends AdminController
{
	/**
	 * {@inheritdoc}
	 */
	public function isFond()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'luring' . DIRECTORY_SEPARATOR . 'admin';
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionSet($id)
	{
		$model = ArchiveLocations::find()
			->where(['archive_id' => $id])
			->one();
		$newRecord = false;
        if ($model == null) {
			$newRecord = true;
			$model = new ArchiveLocations(['archive_id' => $id]);
        }
		$model->archive->isFond = $this->isFond();

        if ($model->archive->isFond == true) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated location.', ['level-name' => $model->archive->levelTitle->message, 'code' => $model->archive->code]));
                if (!Yii::$app->request->isAjax) {
					return $this->redirect(['set', 'id' => $model->archive_id]);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['set', 'id' => $model->archive_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Location {level-name}: {code}', ['level-name' => $model->archive->levelTitle->message, 'code' => $model->archive->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_location', [
			'model' => $model,
			'newRecord' => $newRecord,
		]);
	}

	/**
	 * Deletes an existing Archives model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionReset($id)
	{
        if (($model = ArchiveLocations::find()->where(['id' => $id])->one()) === null) {
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $model->archive->isFond = $this->isFond();
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive location success reset.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['set', 'id' => $id]);
	}
}
