<?php
/**
 * Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 17:20 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archives".
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\archive\models\Archives as ArchivesModel;
use ommu\archive\models\ArchiveMedia;

class Archives extends ArchivesModel
{
	public $gridForbiddenColumn = ['archive_type', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $isSchema = false;

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        $this->templateColumns = [];

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
			'format' => 'html',
		];
		$this->templateColumns['creator'] = [
			'attribute' => 'creator',
			'label' => Yii::t('app', 'Creator'),
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getCreators(true, 'title'), null, ', ');
			},
			'format' => 'html',
			'visible' => $this->isFond || ($this->isFond && $this->isSchema) ? true : false,
		];
		$this->templateColumns['archive_date'] = [
			'attribute' => 'archive_date',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_date;
			},
		];
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'label' => Yii::t('app', 'Medium'),
			'value' => function($model, $key, $index, $column) {
                if (strtolower($model->levelTitle->message) == 'item') {
                    return $model->medium ? $model->medium : '-';
                }
				// return self::parseChilds($model->getChilds(['sublevel' => false, 'back3nd' => true]), $model->id);
			},
			'filter' => false,
			'enableSorting' => false,
			'contentOptions' => ['class' => 'text-nowrap'],
			'format' => 'raw',
			'visible' => !$this->isFond ? true : false,
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'label' => Yii::t('app', 'Media'),
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getMedias(true, 'title'), null, ', ');
			},
			'filter' => ArchiveMedia::getMedia(),
			'format' => 'html',
			'visible' => !$this->isFond ? true : false,
		];
		$this->templateColumns['archive_type'] = [
			'attribute' => 'archive_type',
			'value' => function($model, $key, $index, $column) {
                if ($model->archive_type) {
                    return self::getArchiveType($model->archive_type);
                }
                return '-';
			},
			'filter' => self::getArchiveType(),
			'visible' => !$this->isFond ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'label' => Yii::t('app', 'Status'),
			'value' => function($model, $key, $index, $column) {
				return self::getPublish($model->publish);
			},
			'filter' => self::getPublish(),
			'contentOptions' => ['class' => 'text-center'],
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
		$this->templateColumns['oFile'] = [
			'attribute' => 'oFile',
			'label' => Yii::t('app', 'Document'),
			'value' => function($model, $key, $index, $column) {
                $senaraiFile = Html::a(Yii::t('app', 'Generate'), ['luring/document/create', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Generate Senarai Luring'), 'class' => 'modal-btn']);
                $oFile = $model->grid->luring;
                if ($oFile) {
                    $senaraiFile = Html::a('<span class="glyphicon glyphicon-ok"></span>', ['luring/document/manage', 'archive' => $model->primaryKey], ['title' => Yii::t('app', 'View Senarai Luring'), 'data-pjax' => 0]);
                }
				return $senaraiFile;
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => $this->isFond && !$this->isSchema ? true : false,
		];
		$this->templateColumns['location'] = [
			'attribute' => 'location',
			'value' => function($model, $key, $index, $column) {
                $location = $model->getLocations(false) != null ? 1 : 0;
                $parseLocation = $location ? '<span class="glyphicon glyphicon-ok"></span>' : Yii::t('app', 'Set Location');
				return Html::a($parseLocation, ['location/set', 'id' => $model->primaryKey], ['title' => $location ? Yii::t('app', 'Location') : Yii::t('app', 'Set Location'), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !$this->isFond ? true : false,
		];
		$this->templateColumns['sync_schema'] = [
			'attribute' => 'sync_schema',
			'label' => Yii::t('app', 'Sync Schema'),
			'value' => function($model, $key, $index, $column) {
                if ($model->sync_schema) {
                    return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['schema/admin/tree', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'View Schema'), 'data-pjax' => 0]);
                }
				return $this->quickAction(Url::to(['run', 'id' => $model->primaryKey]), $model->publish, 'Sync,Sync');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => $this->isFond || ($this->isFond && $this->isSchema) ? true : false,
		];
	}
}
