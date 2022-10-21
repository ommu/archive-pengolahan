<?php
/**
 * ArchivePengolahanImport
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 21 October 2022, 06:03 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_import".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_import":
 * @property integer $id
 * @property string $type
 * @property string $original_filename
 * @property string $custom_filename
 * @property integer $all
 * @property integer $error
 * @property string $log
 * @property integer $rollback
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan[] $penyerahans
 * @property ArchivePengolahanPenyerahanItem[] $items
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use app\models\Users;

class ArchivePengolahanImport extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['log', 'creationDisplayname'];

    public $filename;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_import';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type', 'original_filename', 'custom_filename', 'all', 'error', 'log'], 'required'],
			[['all', 'error', 'rollback', 'creation_id'], 'integer'],
			[['type', 'original_filename', 'custom_filename', 'log'], 'string'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'type' => Yii::t('app', 'Type'),
			'original_filename' => Yii::t('app', 'Original Filename'),
			'custom_filename' => Yii::t('app', 'Custom Filename'),
			'all' => Yii::t('app', 'All'),
			'error' => Yii::t('app', 'Error'),
			'log' => Yii::t('app', 'Log'),
			'rollback' => Yii::t('app', 'Rollback'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'filename' => Yii::t('app', 'Filename'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPenyerahans($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArchivePengolahanPenyerahan::className(), ['import_id' => 'id'])
				->alias('penyerahans')
				->andOnCondition([sprintf('%s.publish', 'penyerahans') => $publish]);
        }

		$model = ArchivePengolahanPenyerahan::find()
            ->alias('t')
            ->where(['t.import_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$penyerahans = $model->count();

		return $penyerahans ? $penyerahans : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getItems($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArchivePengolahanPenyerahanItem::className(), ['import_id' => 'id'])
				->alias('items')
				->andOnCondition([sprintf('%s.publish', 'items') => $publish]);
        }

		$model = ArchivePengolahanPenyerahanItem::find()
            ->alias('t')
            ->where(['t.import_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$items = $model->count();

		return $items ? $items : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanImport the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanImport(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

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
		$this->templateColumns['type'] = [
			'attribute' => 'type',
			'value' => function($model, $key, $index, $column) {
				return self::getType($model->type);
			},
			'filter' => self::getType(),
		];
		$this->templateColumns['filename'] = [
			'attribute' => 'filename',
			'value' => function($model, $key, $index, $column) {
				return $model->parseFilename();
			},
		];
		$this->templateColumns['all'] = [
			'attribute' => 'all',
			'value' => function($model, $key, $index, $column) {
				return $model->all;
			},
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['error'] = [
			'attribute' => 'error',
			'value' => function($model, $key, $index, $column) {
				return $model->error;
			},
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['log'] = [
			'attribute' => 'log',
			'value' => function($model, $key, $index, $column) {
				return $model->log;
			},
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
		$this->templateColumns['rollback'] = [
			'attribute' => 'rollback',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->rollback);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getType
	 */
	public static function getType($value=null)
	{
		$items = array(
			'pengolahan' => Yii::t('app', 'Pengolahan'),
			'item' => Yii::t('app', 'Item'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * function parseFilename
	 */
	public static function parseFilename()
	{
		return $this->original_filename. '' .$this->custom_filename;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->penyerahan = $this->getPenyerahans(true) ? 1 : 0;
		// $this->item = $this->getItems(true) ? 1 : 0;
	}
}
