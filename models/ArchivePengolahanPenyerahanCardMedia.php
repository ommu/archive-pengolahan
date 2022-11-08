<?php
/**
 * ArchivePengolahanPenyerahanCardMedia
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 20:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_card_media".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_card_media":
 * @property integer $id
 * @property string $card_id
 * @property integer $media_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahanCard $card
 * @property ArchiveMedia $media
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use app\models\Users;
use ommu\archive\models\ArchiveMedia;
use app\models\SourceMessage;

class ArchivePengolahanPenyerahanCardMedia extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['creationDisplayname'];

    public $stayInHere;

	public $cardArchiveDescription;
	public $mediaName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_card_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['card_id', 'media_id'], 'required'],
			[['media_id', 'creation_id', 'stayInHere'], 'integer'],
			[['stayInHere'], 'safe'],
			[['card_id'], 'string', 'max' => 32],
			[['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahanCard::className(), 'targetAttribute' => ['card_id' => 'id']],
			[['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveMedia::className(), 'targetAttribute' => ['media_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'card_id' => Yii::t('app', 'Card'),
			'media_id' => Yii::t('app', 'Media'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'cardArchiveDescription' => Yii::t('app', 'Card'),
			'mediaName' => Yii::t('app', 'Media'),
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
	public function getMedia()
	{
		return $this->hasOne(ArchiveMedia::className(), ['id' => 'media_id'])
            ->select(['id', 'media_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMediaTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'media_name'])
            ->select(['id', 'message'])
            ->via('media');
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCardMedia the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCardMedia(get_called_class());
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
		$this->templateColumns['cardArchiveDescription'] = [
			'attribute' => 'cardArchiveDescription',
			'value' => function($model, $key, $index, $column) {
				return isset($model->card) ? $model->card->archive_description : '-';
				// return $model->cardArchiveDescription;
			},
			'visible' => !Yii::$app->request->get('card') ? true : false,
		];
		$this->templateColumns['media_id'] = [
			'attribute' => 'media_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->mediaTitle) ? $model->mediaTitle->message : '-';
				// return $model->mediaName;
			},
			'filter' => ArchiveMedia::getMedia(),
			'visible' => !Yii::$app->request->get('media') ? true : false,
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->cardArchiveDescription = isset($this->card) ? $this->card->archive_description : '-';
		// $this->mediaName = isset($this->media) ? $this->media->title->message : '-';
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
}
