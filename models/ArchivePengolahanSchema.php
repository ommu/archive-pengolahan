<?php
/**
 * ArchivePengolahanSchema
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:04 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_schema".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_schema":
 * @property string $id
 * @property integer $publish
 * @property string $parent_id
 * @property integer $archive_id
 * @property string $code
 * @property string $title
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Archives $archive
 * @property ArchivePengolahanSchemaCard[] $cards
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;
use thamtech\uuid\helpers\UuidHelper;

class ArchivePengolahanSchema extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['creation_date', 'modified_date', 'updated_date', 'parentTitle', 'archiveTitle', 'creationDisplayname', 'modifiedDisplayname'];

    public $stayInHere;
	public $isFond = true;
	public $isManuver = false;

	public $parentTitle;
	public $archiveTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $oChild;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_schema';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'code', 'title'], 'required'],
			[['publish', 'archive_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['id', 'parent_id', 'title'], 'string'],
			[['parent_id', 'archive_id', 'stayInHere'], 'safe'],
			[['id', 'code'], 'string', 'max' => 36],
			[['id'], 'unique'],
			[['archive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['archive_id' => 'id']],
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
			'parent_id' => Yii::t('app', 'Parent'),
			'archive_id' => Yii::t('app', 'Archive'),
			'code' => Yii::t('app', 'Code'),
			'title' => Yii::t('app', 'Title'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'parentTitle' => Yii::t('app', 'Parent (Tree)'),
			'archiveTitle' => Yii::t('app', 'From Archive'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oChild' => Yii::t('app', 'Childs'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCards($count=false, $publish=1)
	{
        if ($count == false) {
            $model = $this->hasMany(ArchivePengolahanSchemaCard::className(), ['schema_id' => 'id'])
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
            ->where(['t.schema_id' => $this->id]);
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
	public function getChilds($count=false, $publish=1)
	{
        if ($count == false) {
            $model = $this->hasMany(ArchivePengolahanSchema::className(), ['parent_id' => 'id'])
				->alias('childs');
                if ($publish != null) {
                    $model->andOnCondition([sprintf('%s.publish', 'childs') => $publish]);
                } else {
                    $model->andOnCondition(['IN', sprintf('%s.publish', 'childs'), [0,1]]);
                }
    
                return $model;
        }

		$model = ArchivePengolahanSchema::find()
            ->alias('t')
            ->where(['t.parent_id' => $this->id]);
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
		$childs = $model->count();

		return $childs ? $childs : 0;
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
	public function getParent()
	{
		return $this->hasOne(ArchivePengolahanSchema::className(), ['id' => 'parent_id'])
            ->select(['id', 'parent_id', 'archive_id', 'code', 'title']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanSchema the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanSchema(get_called_class());
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
		$this->templateColumns['parentTitle'] = [
			'attribute' => 'parentTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->title : '-';
			},
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('parent') ? true : false,
		];
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
			'format' => 'raw',
		];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
			'visible' => !$this->isManuver ? true : false,
		];
		$this->templateColumns['archiveTitle'] = [
			'attribute' => 'archiveTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->archive) ? $model->archive->title : '-';
				// return $model->archiveTitle;
			},
			'visible' => !Yii::$app->request->get('archive') ? true : false,
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
        $this->templateColumns['oChild'] = [
            'attribute' => 'oChild',
            'value' => function($model, $key, $index, $column) {
                $childs = $model->getChilds(true);
                return Html::a($childs, ['schema/admin/manage', 'parent' => $model->primaryKey], ['title' => Yii::t('app', '{count} childs', ['count' => $childs]), 'data-pjax' => 0]);
            },
			'filter' => $this->filterYesNo(),
            'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
			'visible' => !$this->isManuver ? true : false,
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
			'visible' => !$this->isManuver && !Yii::$app->request->get('trash') ? true : false,
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
	 * function parseParent
	 */
	public static function parseParent($model, $aciTree=true)
	{
        if (!isset($model)) {
            return Yii::$app->request->isAjax ? '-' : '<div id="tree" class="aciTree"></div>';
        }

		$title = self::htmlHardDecode($model->title);

		$items[] = $model->getAttributeLabel('title').': '.Html::a($title, ['view', 'id' => $model->id], ['title' => $title, 'class' => 'modal-btn']);

        if (Yii::$app->request->isAjax) {
            return Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
        }
		
		$return = Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
        if ($aciTree) {
            $return .= '<hr/><div id="tree" class="aciTree"></div>';
        }
		return $return;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

        $this->isFond = $this->parent_id == '' ? true : false;
		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->card = $this->getCards(true) ? 1 : 0;
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
}
