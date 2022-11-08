<?php
/**
 * ArchivePengolahanPenyerahanCard
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 11:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_card".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_card":
 * @property string $id
 * @property integer $publish
 * @property integer $penyerahan_id
 * @property integer $user_id
 * @property string $temporary_number
 * @property string $archive_type
 * @property string $from_archive_date
 * @property string $to_archive_date
 * @property string $medium
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $penyerahan
 * @property ArchivePengolahanPenyerahanCardMedia[] $media
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ArchivePengolahanPenyerahanCard extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['creation_date', 'modified_date', 'updated_date', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname'];

    public $stayInHere;

	public $penyerahanTypeId;
	public $penyerahanPenciptaArsip;
	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'penyerahan_id', 'user_id', 'temporary_number', 'from_archive_date', 'to_archive_date', 'medium'], 'required'],
			[['publish', 'penyerahan_id', 'user_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['archive_type', 'medium'], 'string'],
			[['stayInHere'], 'safe'],
			[['id', 'temporary_number'], 'string', 'max' => 32],
			[['from_archive_date', 'to_archive_date'], 'string', 'max' => 64],
			[['id'], 'unique'],
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
			'user_id' => Yii::t('app', 'User'),
			'temporary_number' => Yii::t('app', 'Temporary Number'),
			'archive_type' => Yii::t('app', 'Archive Type'),
			'from_archive_date' => Yii::t('app', 'From Archive Date'),
			'to_archive_date' => Yii::t('app', 'To Archive Date'),
			'medium' => Yii::t('app', 'Medium'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'penyerahanTypeId' => Yii::t('app', 'Penyerahan Type'),
			'penyerahanPenciptaArsip' => Yii::t('app', 'Kode Box / Pencipta Arsip'),
			'userDisplayname' => Yii::t('app', 'User'),
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
	public function getMedia($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ArchivePengolahanPenyerahanCardMedia::className(), ['card_id' => 'id']);
        }

		$model = ArchivePengolahanPenyerahanCardMedia::find()
            ->alias('t')
            ->where(['t.card_id' => $this->id]);
		$media = $model->count();

		return $media ? $media : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'displayname']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCard the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCard(get_called_class());
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
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['temporary_number'] = [
			'attribute' => 'temporary_number',
			'value' => function($model, $key, $index, $column) {
				return $model->temporary_number;
			},
		];
		$this->templateColumns['archive_type'] = [
			'attribute' => 'archive_type',
			'value' => function($model, $key, $index, $column) {
				return self::getArchiveType($model->archive_type);
			},
			'filter' => self::getArchiveType(),
		];
		$this->templateColumns['from_archive_date'] = [
			'attribute' => 'from_archive_date',
			'value' => function($model, $key, $index, $column) {
				return $model->from_archive_date;
			},
		];
		$this->templateColumns['to_archive_date'] = [
			'attribute' => 'to_archive_date',
			'value' => function($model, $key, $index, $column) {
				return $model->to_archive_date;
			},
		];
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'value' => function($model, $key, $index, $column) {
				return $model->medium;
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
	 * function getArchiveType
	 */
	public static function getArchiveType($value=null)
	{
		$items = array(
			'photo' => Yii::t('app', 'Photo'),
			'text' => Yii::t('app', 'Text'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->penyerahanTypeId = isset($this->penyerahan) ? $this->penyerahan->type->type_name : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->media = $this->getMedia(true) ? 1 : 0;
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
