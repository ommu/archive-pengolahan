<?php
/**
 * ArchivePengolahanPenyerahanCardSubject
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 November 2022, 01:01 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_card_subject".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_card_subject":
 * @property integer $id
 * @property string $type
 * @property string $card_id
 * @property integer $tag_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahanCard $card
 * @property CoreTags $tag
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Inflector;
use app\models\CoreTags;
use app\models\Users;

class ArchivePengolahanPenyerahanCardSubject extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

    public $stayInHere;

	public $tagBody;
	public $cardArchiveDescription;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_card_subject';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type', 'card_id', 'tagBody'], 'required'],
			[['tag_id', 'creation_id', 'stayInHere'], 'integer'],
			[['type', 'tagBody'], 'string'],
			[['stayInHere'], 'safe'],
			[['card_id'], 'string', 'max' => 36],
			[['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahanCard::className(), 'targetAttribute' => ['card_id' => 'id']],
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
			'card_id' => Yii::t('app', 'Card'),
			'tag_id' => Yii::t('app', 'Tag'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'tagBody' => Yii::t('app', 'Tag'),
			'cardArchiveDescription' => Yii::t('app', 'Card'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCard()
	{
		return $this->hasOne(ArchivePengolahanPenyerahanCard::className(), ['id' => 'card_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTag()
	{
		return $this->hasOne(CoreTags::className(), ['tag_id' => 'tag_id'])
            ->select(['tag_id', 'body']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCardSubject the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCardSubject(get_called_class());
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
		$this->templateColumns['cardArchiveDescription'] = [
			'attribute' => 'cardArchiveDescription',
			'value' => function($model, $key, $index, $column) {
				return isset($model->card) ? $model->card->archive_description : '-';
				// return $model->cardArchiveDescription;
			},
			'visible' => !Yii::$app->request->get('card') ? true : false,
		];
		$this->templateColumns['tagBody'] = [
			'attribute' => 'tagBody',
			'value' => function($model, $key, $index, $column) {
				return isset($model->tag) ? $model->tag->body : '-';
				// return $model->tagBody;
			},
			'visible' => !Yii::$app->request->get('tag') ? true : false,
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
			'subject' => Yii::t('app', 'Subject'),
			'function' => Yii::t('app', 'Function'),
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

		// $this->tagBody = isset($this->tag) ? $this->tag->body : '';
		// $this->cardArchiveDescription = isset($this->card) ? $this->card->archive_description : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
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
            if ($insert) {
                $tagBody = Inflector::camelize($this->tagBody);
                if ($this->tag_id == 0) {
                    $tag = CoreTags::find()
                        ->select(['tag_id'])
                        ->andWhere(['body' => $tagBody])
                        ->one();
                        
                    if ($tag != null) {
                        $this->tag_id = $tag->tag_id;
                    } else {
                        $data = new CoreTags();
                        $data->body = $this->tagBody;
                        if($data->save())
                            $this->tag_id = $data->tag_id;
                    }
                }
            }
        }
        return true;
	}
}
