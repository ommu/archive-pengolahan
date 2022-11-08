<?php
/**
 * SyncController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Index
 *  Schema
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:45 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\archive\models\Archives;
use ommu\archivePengolahan\models\ArchivePengolahanSchema;
use yii\helpers\ArrayHelper;

class SyncController extends Controller
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
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return [];
	}

	/**
	 * Index Action
	 */
	public function actionIndex()
	{
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Schema Action
	 */
	public function actionSchema()
	{
        $fonds = Archives::find()->alias('t')
            ->select(['id', 'parent_id', 'level_id', 'title', 'code'])
            ->andWhere(['in', 'id', [34601, 34602, 35160, 35164, 36503, 36659, 36663, 38435, 38456, 40532]])
            ->andWhere(['in', 't.publish', [0,1]])
            ->andWhere(['is', 't.parent_id', null])
            ->andWhere(['t.level_id' => 1])
            ->andWhere(['t.sync_schema' => 0])
			->limit(2)
            ->all();

        $data = [];
        if ($fonds) {
            // echo '<pre>';
            foreach ($fonds as $fond) {
                $data[$fond->id]['id'] = $fond->id;
                $data[$fond->id]['parent_id'] = $fond->parent_id;
                $data[$fond->id]['level_id'] = $fond->level_id;
                $data[$fond->id]['title'] = $fond->title;
                $data[$fond->id]['code'] = $fond->code;
                $data[$fond->id]['shortCode'] = $fond->shortCode;

                $archive = $this->getData($fond);
                $data[$fond->id] = ArrayHelper::merge($data[$fond->id], ['childs' => $archive]);
            }
            // print_r($data);
            // echo '</pre>';
        }
        // exit();

        if ($data) {
            foreach ($data as $row) {
                $model = new ArchivePengolahanSchema();
                $model->archive_id = $row['id'];
                $model->code = $row['shortCode'];
                $model->title = $row['title'];
                if ($model->save()) {
					Archives::updateAll(['sync_schema' => 1], ['id' => $row['id']]);
                    if (!empty($row['childs'])) {
                        $this->getInsert($row['childs'], $model->id);
                    }
                }
            }
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Schema success sync.'));
        return $this->redirect(['/admin/dashboard/index']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData($archive)
	{
        $childs = $archive->getArchives()
            ->select(['id', 'parent_id', 'level_id', 'title', 'code'])
            ->andWhere(['<>', 't.level_id', 8])
            ->all();

        $data = [];
        if ($childs) {
            foreach ($childs as $child) {
                $data[$child->id]['id'] = $child->id;
                $data[$child->id]['parent_id'] = $child->parent_id;
                $data[$child->id]['level_id'] = $child->level_id;
                $data[$child->id]['title'] = $child->title;
                $data[$child->id]['code'] = $child->code;
                $data[$child->id]['shortCode'] = $child->shortCode;

                $archive = $this->getData($child);
                $data[$child->id] = ArrayHelper::merge($data[$child->id], ['childs' => $archive]);
            }
        }

        return $data;
    }

	/**
	 * {@inheritdoc}
	 */
	public function getInsert($childs, $parentId)
	{
        foreach ($childs as $row) {
            $model = new ArchivePengolahanSchema();
            $model->parent_id = $parentId;
            $model->archive_id = $row['id'];
            $model->code = $row['shortCode'];
            $model->title = $row['title'];
            if ($model->save()) {
                Archives::updateAll(['sync_schema' => 1], ['id' => $row['id']]);
                if (!empty($row['childs'])) {
                    $this->getInsert($row['childs'], $model->id);
                }
            }
        }

        return;
    }

}
