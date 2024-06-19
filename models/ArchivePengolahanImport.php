<?php
/**
 * ArchivePengolahanImport
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
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
use yii\helpers\Url;
use app\models\Users;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

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
			[['type', 'original_filename', 'custom_filename'], 'required'],
			[['all', 'error', 'rollback', 'creation_id'], 'integer'],
			[['type', 'original_filename', 'custom_filename'], 'string'],
			//[['log'], 'json'],
			[['all', 'error', 'log'], 'safe'],
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
	public function getItems()
	{
        if ($this->type == 'penyerahan') {
            return $this->hasMany(ArchivePengolahanPenyerahan::className(), ['import_id' => 'id'])
				->alias('penyerahans')
                ->select(['id'])
				->andOnCondition(['IN', sprintf('%s.publish', 'penyerahans'), [0,1]]);
 
        } else if ($this->type == 'item') {
            return $this->hasMany(ArchivePengolahanPenyerahanItem::className(), ['import_id' => 'id'])
				->alias('items')
                ->select(['id'])
				->andOnCondition(['IN', sprintf('%s.publish', 'items'), [0,1]]);
        }
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
			'format' => 'raw',
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
				return Json::encode($model->log);
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
				$url = Url::to(['rollback', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->rollback, 'Rollback,Rollback', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
			'penyerahan' => Yii::t('app', 'Penyerahan'),
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
	public function parseFilename()
	{
        $uploadPath = join('/', [ArchivePengolahanPenyerahan::getUploadPath(false), '_import']);
        $items[] = Yii::t('app', 'Original: {filename}', ['filename' => $this->original_filename]);
        $items[] = Yii::t('app', 'Custom: {filename}', ['filename' => Html::a($this->custom_filename, Url::to(join('/', ['@webpublic', $uploadPath, $this->custom_filename])), ['title' => $this->custom_filename, 'data-pjax' => 0, 'target' => '_blank'])]);

		return Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

        if ($this->log == '') {
            $this->log = [];
        } else {
            $this->log = Json::decode($this->log);
        }

		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->penyerahan = $this->getPenyerahans(true) ? 1 : 0;
		// $this->item = $this->getItems(true) ? 1 : 0;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
			$this->log = Json::encode($this->log);
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            if (array_key_exists('rollback', $changedAttributes) && $changedAttributes['rollback'] != $this->rollback && $this->rollback == 1) {
                $items = ArrayHelper::map($this->items, 'id', 'id');

                if ($this->type == 'penyerahan') {
                    ArchivePengolahanPenyerahan::updateAll(['publish' => 2], ['IN', 'id', $items]);
                } else if ($this->type == 'item') {
                    ArchivePengolahanPenyerahanItem::updateAll(['publish' => 2], ['IN', 'id', $items]);
                }
            }
		}
	}
}
