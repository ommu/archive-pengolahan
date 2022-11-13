<?php
/**
 * ArchivePengolahanPenyerahanType
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 07:51 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_type".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_type":
 * @property integer $id
 * @property integer $publish
 * @property string $type_name
 * @property string $type_desc
 * @property string $feature
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan[] $penyerahans
 * @property ArchivePengolahanPenyerahanTypeGrid $grid
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\Users;

class ArchivePengolahanPenyerahanType extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $stayInHere;

	public $creationDisplayname;
	public $modifiedDisplayname;
	public $oPenyerahan;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type_name', 'type_desc'], 'required'],
			[['publish', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			//[['feature'], 'json'],
			[['feature', 'stayInHere'], 'safe'],
			[['type_name'], 'string', 'max' => 64],
			[['type_desc'], 'string', 'max' => 256],
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
			'type_name' => Yii::t('app', 'Type Name'),
			'type_desc' => Yii::t('app', 'Type Desc'),
			'feature' => Yii::t('app', 'Feature'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oPenyerahan' => Yii::t('app', 'Penyerahan'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPenyerahans($count=false, $publish=1)
	{
        if ($count == false) {
            $model = $this->hasMany(ArchivePengolahanPenyerahan::className(), ['type_id' => 'id'])
				->alias('penyerahans');
            if ($publish != null) {
                $model->andOnCondition([sprintf('%s.publish', 'penyerahans') => $publish]);
            } else {
                $model->andOnCondition(['IN', sprintf('%s.publish', 'penyerahans'), [0,1]]);
            }

            return $model;
        }

		$model = ArchivePengolahanPenyerahan::find()
            ->alias('t')
            ->where(['t.type_id' => $this->id]);
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
		$penyerahans = $model->count();

		return $penyerahans ? $penyerahans : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGrid()
	{
		return $this->hasOne(ArchivePengolahanPenyerahanTypeGrid::className(), ['id' => 'id']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanType the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanType(get_called_class());
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
		$this->templateColumns['type_name'] = [
			'attribute' => 'type_name',
			'value' => function($model, $key, $index, $column) {
				return $model->type_name;
			},
		];
		$this->templateColumns['type_desc'] = [
			'attribute' => 'type_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->type_desc;
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
		$this->templateColumns['oPenyerahan'] = [
			'attribute' => 'oPenyerahan',
			'value' => function($model, $key, $index, $column) {
				// $penyerahans = $model->getPenyerahans(true);
				$penyerahans = $model->grid->penyerahan;
				return Html::a($penyerahans, ['penyerahan/admin/manage', 'type' => $model->primaryKey], ['title' => Yii::t('app', '{count} penyerahan', ['count' => $penyerahans]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
	 * function getType
	 */
	public static function getType($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.type_name']);
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }

		$model = $model->orderBy('t.type_name ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'type_name');
        }

		return $model;
	}

	/**
	 * function getFeature
	 */
	public static function getFeature($feature=null, $sep='li')
	{
        $items = array(
            'publication' => Yii::t('app', 'Unggah Publikasi'),
            'item' => Yii::t('app', 'Tambahkan Item Arsip'),
        );

        if ($feature !== null) {
            if (!is_array($feature) || (is_array($feature) && empty($feature))) {
                return '-';
            }

			$item = [];
			foreach ($items as $key => $val) {
                if (in_array($key, $feature)) {
                    $item[$key] = $val;
                }
			}

            if ($sep == 'li') {
				return Html::ul($item, ['item' => function($item, $index) {
					return Html::tag('li', "($index) $item");
				}, 'class' => 'list-boxed']);
			}

			return implode(', ', $item);

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

        if ($this->feature == '') {
            $this->feature = [];
        } else {
            $this->feature = Json::decode($this->feature);
        }
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->penyerahan = $this->getPenyerahans(true) ? 1 : 0;
		// $this->oPenyerahan = isset($this->grid) ? $this->grid->penyerahan : 0;
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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
			$this->feature = Json::encode($this->feature);
        }
        return true;
	}
}
