<?php
/**
 * ArchivePengolahanPenyerahanItem
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:17 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_item".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_item":
 * @property integer $id
 * @property integer $publish
 * @property integer $penyerahan_id
 * @property string $archive_number
 * @property string $archive_description
 * @property string $year
 * @property string $volume
 * @property string $code
 * @property string $description
 * @property integer $import_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $penyerahan
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ArchivePengolahanPenyerahanItem extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['description', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

    public $stayInHere;

	public $penyerahanTypeId;
	public $penyerahanPenciptaArsip;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['penyerahan_id', 'archive_number', 'archive_description', 'year', 'volume', 'description'], 'required'],
			[['publish', 'penyerahan_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['archive_description'], 'string'],
			[['code', 'stayInHere'], 'safe'],
			[['archive_number', 'volume', 'code'], 'string', 'max' => 16],
			[['year'], 'string', 'max' => 8],
			[['description'], 'string', 'max' => 64],
			[['penyerahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahan::className(), 'targetAttribute' => ['penyerahan_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'penyerahan_id' => Yii::t('app', 'Penyerahan'),
			'archive_number' => Yii::t('app', 'Archive Number'),
			'archive_description' => Yii::t('app', 'Archive Description'),
			'year' => Yii::t('app', 'Year'),
			'volume' => Yii::t('app', 'Volume'),
			'code' => Yii::t('app', 'Code'),
			'description' => Yii::t('app', 'Description'),
			'import_id' => Yii::t('app', 'Import'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'penyerahanTypeId' => Yii::t('app', 'Penyerahan Type'),
			'penyerahanPenciptaArsip' => Yii::t('app', 'Kode Box / Pencipta Arsip'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPenyerahan()
	{
		return $this->hasOne(ArchivePengolahanPenyerahan::className(), ['id' => 'penyerahan_id'])
            ->select(['id', 'type_id', 'kode_box', 'pencipta_arsip']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(ArchivePengolahanPenyerahanType::className(), ['id' => 'type_id'])
            ->select(['id', 'type_name', 'feature'])
            ->via('penyerahan');
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanItem the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanItem(get_called_class());
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
		$this->templateColumns['penyerahanTypeId'] = [
			'attribute' => 'penyerahanTypeId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->type) ? $model->type->type_name : '-';
				// return $model->penyerahanTypeId;
			},
			'filter' => ArchivePengolahanPenyerahanType::getType(),
			'visible' => !Yii::$app->request->get('penyerahan') && !Yii::$app->request->get('type') ? true : false,
		];
		$this->templateColumns['penyerahanPenciptaArsip'] = [
			'attribute' => 'penyerahanPenciptaArsip',
			'value' => function($model, $key, $index, $column) {
				return $model->penyerahan::parsePenyerahan($model->penyerahan, false);
				// return $model->penyerahanPenciptaArsip;
			},
			'visible' => !Yii::$app->request->get('penyerahan') ? true : false,
            'format' => 'raw',
		];
		$this->templateColumns['archive_number'] = [
			'attribute' => 'archive_number',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_number;
			},
		];
		$this->templateColumns['archive_description'] = [
			'attribute' => 'archive_description',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_description;
			},
            'format' => 'html',
		];
		$this->templateColumns['year'] = [
			'attribute' => 'year',
			'value' => function($model, $key, $index, $column) {
				return $model->year;
			},
		];
		$this->templateColumns['volume'] = [
			'attribute' => 'volume',
			'value' => function($model, $key, $index, $column) {
				return $model->volume;
			},
		];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
				return $model->description;
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
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->penyerahanTypeId = isset($this->penyerahan) ? $this->penyerahan->type->type_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
