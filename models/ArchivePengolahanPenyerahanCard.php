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
 * @property string $archive_description
 * @property string $archive_type
 * @property string $from_archive_date
 * @property string $to_archive_date
 * @property string $archive_date
 * @property string $medium
 * @property string $medium_json
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $penyerahan
 * @property ArchivePengolahanUsers $user
 * @property ArchivePengolahanPenyerahanCardMedia[] $media
 * @property Users $creation
 * @property Users $modified
 * @property ArchiveRelatedSubject[] $subjects
 * @property ArchiveRelatedSubject[] $functions
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\Users;
use thamtech\uuid\helpers\UuidHelper;
use ommu\archive\models\ArchiveMedia;
use yii\base\Event;

class ArchivePengolahanPenyerahanCard extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['creation_date', 'modified_date', 'updated_date', 'media', 'subject', 'function', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname'];

    public $stayInHere;

    public $media;
    public $subject;
    public $function;

	public $penyerahanTypeId;
	public $penyerahanPenciptaArsip;
	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;
    public $subjectId;
    public $functionId;

	const EVENT_BEFORE_SAVE_PENYERAHAN_CARD = 'BeforeSavePenyerahanCard';

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
			[['id', 'penyerahan_id', 'user_id', 'temporary_number', 'archive_description'], 'required'],
			[['publish', 'penyerahan_id', 'user_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['id', 'archive_description', 'archive_type'], 'string'],
			//[['archive_date', 'medium_json'], 'json'],
			[['from_archive_date', 'to_archive_date', 'archive_date', 'medium', 'medium_json', 'stayInHere', 'media', 'subject', 'function'], 'safe'],
			[['id', 'temporary_number'], 'string', 'max' => 36],
			[['from_archive_date', 'to_archive_date'], 'string', 'max' => 64],
			[['medium'], 'string', 'max' => 255],
			[['id'], 'unique'],
			[['penyerahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahan::className(), 'targetAttribute' => ['penyerahan_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
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
			'archive_description' => Yii::t('app', 'Archive Description'),
			'archive_type' => Yii::t('app', 'Archive Type'),
			'from_archive_date' => Yii::t('app', 'From Archive Date'),
			'to_archive_date' => Yii::t('app', 'To Archive Date'),
			'archive_date' => Yii::t('app', 'Archive Date'),
			'medium' => Yii::t('app', 'Medium'),
			'medium_json' => Yii::t('app', 'Medium'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'media' => Yii::t('app', 'Media Type'),
			'subject' => Yii::t('app', 'Subject'),
			'function' => Yii::t('app', 'Function'),
			'penyerahanTypeId' => Yii::t('app', 'Penyerahan Type'),
			'penyerahanPenciptaArsip' => Yii::t('app', 'Kode Box / Pencipta Arsip'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'day' => Yii::t('app', 'Day'),
			'month' => Yii::t('app', 'Month'),
			'year' => Yii::t('app', 'Year'),
			'total' => Yii::t('app', 'Total'),
			'unit' => Yii::t('app', 'Unit'),
			'condition' => Yii::t('app', 'Condition'),
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
	public function getMedias($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->medias, 'media_id', $val=='id' ? 'id' : 'mediaTitle.message');
        }

		return $this->hasMany(ArchivePengolahanPenyerahanCardMedia::className(), ['card_id' => 'id'])
            ->select(['id', 'card_id', 'media_id']);
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
	public function getUser()
	{
		return $this->hasOne(ArchivePengolahanUsers::className(), ['id' => 'user_id'])
            ->select(['id', 'publish', 'user_id', 'user_code', 'archives']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMember()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'email', 'displayname'])
            -via('user');
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getSubjects($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->subjects, 'tag_id', $val=='id' ? 'id' : 'tag.body');
        }

		return $this->hasMany(ArchivePengolahanPenyerahanCardSubject::className(), ['card_id' => 'id'])
			->alias('subjects')
            ->select(['id', 'card_id', 'tag_id'])
			->andOnCondition([sprintf('%s.type', 'subjects') => 'subject']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFunctions($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->functions, 'tag_id', $val=='id' ? 'id' : 'tag.body');
        }

		return $this->hasMany(ArchivePengolahanPenyerahanCardSubject::className(), ['card_id' => 'id'])
			->alias('functions')
            ->select(['id', 'card_id', 'tag_id'])
			->andOnCondition([sprintf('%s.type', 'functions') => 'function']);
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
                return $model->user ? $model->user::parseUser($model->user) : '-';
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
		$this->templateColumns['archive_description'] = [
			'attribute' => 'archive_description',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_description;
			},
			'format' => 'raw',
		];
		$this->templateColumns['from_archive_date'] = [
			'attribute' => 'from_archive_date',
			'label' => Yii::t('app', 'From Date'),
			'value' => function($model, $key, $index, $column) {
				return $model->from_archive_date;
			},
		];
		$this->templateColumns['to_archive_date'] = [
			'attribute' => 'to_archive_date',
			'label' => Yii::t('app', 'To Date'),
			'value' => function($model, $key, $index, $column) {
				return $model->to_archive_date;
			},
		];
		$this->templateColumns['archive_type'] = [
			'attribute' => 'archive_type',
			'label' => Yii::t('app', 'Type'),
			'value' => function($model, $key, $index, $column) {
                if ($model->archive_type) {
                    return self::getArchiveType($model->archive_type);
                }
                return '-';
			},
			'filter' => self::getArchiveType(),
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'label' => Yii::t('app', 'Media'),
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getMedias(true, 'title'), 'media', ', ');
			},
			'filter' => ArchiveMedia::getMedia(),
			'format' => 'html',
		];
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'value' => function($model, $key, $index, $column) {
				return $model->medium;
			},
		];
		$this->templateColumns['subject'] = [
			'attribute' => 'subject',
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getSubjects(true, 'title'), 'subjectId', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['function'] = [
			'attribute' => 'function',
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getFunctions(true, 'title'), 'functionId', ', ');
			},
			'format' => 'html',
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
	 * function parseFilter
	 */
	public static function parseFilter($medias, $attr='media', $sep='li')
	{
        if (!is_array($medias) || (is_array($medias) && empty($medias))) {
            return '-';
        }

		$items = [];
		foreach ($medias as $key => $val) {
			$items[$val] = Html::a($val, ['penyerahan/card/manage', $attr => $key], ['title' => $val]);
		}

        if ($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class' => 'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

        if ($this->archive_date == '') {
            $this->archive_date = [];
        } else {
            $this->archive_date = Json::decode($this->archive_date);
        }

        if ($this->medium_json == '') {
            $this->medium_json = [];
        } else {
            $this->medium_json = Json::decode($this->medium_json);
        }

		// $this->penyerahanTypeId = isset($this->penyerahan) ? $this->penyerahan->type->type_name : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->media = $this->getMedia(true) ? 1 : 0;
		// $this->subject =  implode(',', $this->getSubjects(true, 'title'));
		// $this->function =  implode(',', $this->getFunctions(true, 'title'));
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->id = UuidHelper::uuid();

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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                // set media
                $event = new Event(['sender' => $this]);
                Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_PENYERAHAN_CARD, $event);
            }

            if (is_array($this->archive_date)) {
                $archive_date = $this->archive_date;
                $this->archive_date = Json::encode($this->archive_date);
                $from_archive_date = array_filter($archive_date['from']);
                $this->from_archive_date = implode(' ', $from_archive_date);
                $to_archive_date = array_filter($archive_date['to']);
                $this->to_archive_date = implode(' ', $to_archive_date);
            }

            if (is_array($this->medium_json)) {
                $medium_json = $this->medium_json;
                $this->medium_json = Json::encode($this->medium_json);
                $medium_json = array_filter($medium_json);
                $this->medium = implode(' ', $medium_json);
            }
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
			// set media
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_PENYERAHAN_CARD, $event);
		}
    }
}
