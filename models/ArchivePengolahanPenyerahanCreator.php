<?php
/**
 * ArchivePengolahanPenyerahanCreator
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 07:54 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_creator".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_creator":
 * @property integer $id
 * @property integer $penyerahan_id
 * @property integer $creator_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $penyerahan
 * @property ArchiveCreator $creator
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use ommu\archive\models\ArchiveCreator;
use app\models\Users;

class ArchivePengolahanPenyerahanCreator extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['penyerahanTypeId', 'creatorName', 'creationDisplayname'];

	public $penyerahanTypeId;
	public $creatorName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_creator';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['penyerahan_id', 'creator_id'], 'required'],
			[['penyerahan_id', 'creator_id', 'creation_id'], 'integer'],
			[['penyerahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahan::className(), 'targetAttribute' => ['penyerahan_id' => 'id']],
			[['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveCreator::className(), 'targetAttribute' => ['creator_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'penyerahan_id' => Yii::t('app', 'Penyerahan'),
			'creator_id' => Yii::t('app', 'Creator'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'penyerahanTypeId' => Yii::t('app', 'Penyerahan'),
			'creatorName' => Yii::t('app', 'Creator'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPenyerahan()
	{
		return $this->hasOne(ArchivePengolahanPenyerahan::className(), ['id' => 'penyerahan_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreator()
	{
		return $this->hasOne(ArchiveCreator::className(), ['id' => 'creator_id'])
            ->select(['id', 'creator_name']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCreator the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanCreator(get_called_class());
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
				return isset($model->penyerahan) ? $model->penyerahan->type->type_name : '-';
				// return $model->penyerahanTypeId;
			},
			'visible' => !Yii::$app->request->get('penyerahan') ? true : false,
		];
		$this->templateColumns['creatorName'] = [
			'attribute' => 'creatorName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creator) ? $model->creator->creator_name : '-';
				// return $model->creatorName;
			},
			'visible' => !Yii::$app->request->get('creator') ? true : false,
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

		// $this->penyerahanTypeId = isset($this->penyerahan) ? $this->penyerahan->type->type_name : '-';
		// $this->creatorName = isset($this->creator) ? $this->creator->creator_name : '-';
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
