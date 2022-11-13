<?php
/**
 * ArchivePengolahanFinal
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_final".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_final":
 * @property integer $id
 * @property integer $publish
 * @property string $fond_name
 * @property integer $archive_start_from
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanSchemaCard[] $cards
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ArchivePengolahanFinal extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['creationDisplayname'];

    public $stayInHere;

	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_final';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['fond_name', 'archive_start_from'], 'required'],
			[['publish', 'archive_start_from', 'creation_id', 'stayInHere'], 'integer'],
			[['stayInHere'], 'safe'],
			[['fond_name'], 'string', 'max' => 64],
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
			'fond_name' => Yii::t('app', 'Fond Name'),
			'archive_start_from' => Yii::t('app', 'Archive Start From'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCards($count=false, $publish=1)
	{
        if ($count == false) {
            $model = $this->hasMany(ArchivePengolahanSchemaCard::className(), ['final_id' => 'id'])
				->alias('cards');
            if ($publish != null) {
                $model->andOnCondition([sprintf('%s.publish', 'cards') => $publish]);
            } else {
                $model->andOnCondition(['IN', sprintf('%s.publish', 'cards'), [0,1]]);
            }

            return $model;
        }

		$model = ArchivePengolahanSchemaCard::find()
            ->alias('t')
            ->where(['t.final_id' => $this->id]);
        if ($publish != null) {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
                $model->deleted();
            }
		} else {
            $model->andWhere(['IN', 't.publish', [0,1]]);
        }
		$cards = $model->count();

		return $cards ? $cards : 0;
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanFinal the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanFinal(get_called_class());
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
		$this->templateColumns['fond_name'] = [
			'attribute' => 'fond_name',
			'value' => function($model, $key, $index, $column) {
				return $model->fond_name;
			},
		];
		$this->templateColumns['archive_start_from'] = [
			'attribute' => 'archive_start_from',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_start_from;
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
				return $this->quickAction($url, $model->publish, 'deleted');
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

		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->card = $this->getCards(true) ? 1 : 0;
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
