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
 * @property string $fond_number
 * @property string $fond_name
 * @property integer $archive_start_from
 * @property string $fond_schema_id
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
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ArchivePengolahanFinal extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['updated_date'];

    public $stayInHere;
    public $cardsId;

	public $creationDisplayname;
    public $oCard;

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
			[['fond_number', 'fond_name', 'archive_start_from'], 'required'],
			[['publish', 'archive_start_from', 'creation_id', 'stayInHere'], 'integer'],
			[['fond_name'], 'string'],
			[['fond_number'], 'string', 'max' => 255],
			[['fond_schema_id'], 'string', 'max' => 36],
			[['fond_schema_id', 'stayInHere', 'cardsId'], 'safe'],
			[['fond_schema_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanSchema::className(), 'targetAttribute' => ['fond_schema_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Status'),
			'fond_number' => Yii::t('app', 'Fond Code'),
			'fond_name' => Yii::t('app', 'Fond Title'),
			'archive_start_from' => Yii::t('app', 'Archive Start From'),
			'fond_schema_id' => Yii::t('app', 'From Schema'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
            'oCard' => Yii::t('app', 'Cards'),
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getSchema()
	{
		return $this->hasOne(ArchivePengolahanSchema::className(), ['id' => 'fond_schema_id'])
            ->select(['id', 'parent_id', 'level_id', 'code', 'title']);
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
		$this->templateColumns['fond_number'] = [
			'attribute' => 'fond_number',
			'value' => function($model, $key, $index, $column) {
				return $model->fond_number;
			},
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
        $this->templateColumns['oCard'] = [
            'attribute' => 'oCard',
            'value' => function($model, $key, $index, $column) {
                $cards = $model->getCards(true);
				return Html::a($cards, ['tree', 'id' => $model->primaryKey], ['title' => Yii::t('app', '{count} cards', ['count' => $cards]), 'data-pjax' => 0]);
            },
            'filter' => false,
            'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
        ];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
                $published = Html::button('<span class="glyphicon glyphicon-ok"></span> '.Yii::t('app', 'Published'), ['class' => 'btn btn-success active btn-xs', 'role' => 'button']);
                if ($model->publish != 1) {
                    $publish = Html::a(Html::button('<span class="glyphicon glyphicon-upload"></span> '.Yii::t('app', 'Publish to Layanan'), ['class' => 'btn btn-warning btn-xs']), ['publish', 'id' => $model->primaryKey], [
                        'title' => Yii::t('app', 'Publish to Layanan'),
                        'data-confirm' => Yii::t('app', 'Are you sure you want to publish to layanan?'),
                        'data-method'  => 'post',
                    ]);
                    $reset = Html::a(Html::button('<span class="glyphicon glyphicon-remove"></span> '.Yii::t('app', 'Reset Finalisasi'), ['class' => 'btn btn-danger btn-xs']), ['delete', 'id' => $model->primaryKey], [
                        'title' => Yii::t('app', 'Reset Finalisasi'),
                        'data-confirm' => Yii::t('app', 'Are you sure you want to reset this finalisasi?'),
                        'data-method'  => 'post',
                    ]);
                    return $publish.$reset;
                }
				return $published;
			},
			'filter' => $this->filterYesNo(),
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
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getArchive()
	{
        $fondSchema = $this->schema;

        if ($fondSchema == null) return [];

        $data = [];
        $data = ArrayHelper::merge($data, [$fondSchema->id => $this->getTreeSchema($fondSchema)]);

        $cards = $this->cards;
        if (is_array($cards) && !empty($cards)) {
            $data = ArrayHelper::merge($data, $this->getArchiveCards($cards));
        }

        $data[$this->fond_schema_id]['code'] = $this->fond_number;
        $data[$this->fond_schema_id]['label'] = $this->fond_name;

        return $data;
    }

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getTreeSchema($model)
	{
		$data[$model->id] = [
			'id' => $model->id,
            'level_id' => $model->level_id,
			'level' => $model->levelTitle->message,
			'code' => $model->code,
			'label' => $model::htmlHardDecode($model->title),
			'inode' => false,
		];

        $childs = $model->getChilds(false, 1)
            ->select(['id', 'level_id', 'code', 'title'])
            ->orderBy('code ASC')
            ->all();

        if (is_array($childs) && !empty($childs)) {
            if ($childs[0]->code == 1) {
                $childs = $model->getChilds(false, 1)
                    ->select(['id', 'level_id', 'code', 'title'])
                    ->orderBy(['cast(code as int)' => SORT_ASC])
                    ->all();
            }
        }

        if ($childs) {
            $cards = [];
            foreach ($childs as $child) {
                $cards[$child->id] = $this->getTreeSchema($child);
            }
            $data[$model->id] = ArrayHelper::merge($data[$model->id], ['open' => true, 'branch' => $cards]);
        }

		return $data[$model->id];
    }

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getArchiveCards($cards)
	{
        $data = [];
        if (is_array($cards) && !empty($cards)) {
            $i = 0;
            foreach ($cards as $card) {
                $data = ArrayHelper::merge($data, $this->getParentSchema($card->schema, [$i => [
                    'id' => $card->card_id,
                    'level_id' => 8,
                    'level' => 'Item',
                    'code' => 'newitem',
                    'label' => $card::htmlHardDecode($card->card->archive_description),
                    'inode' => false,
                ]]));
                $i++;
            }
        }

        return $data;
    }

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getParentSchema($model, $codes)
	{
		$data[$model->id] = [
			'id' => $model->id,
            'level_id' => $model->level_id,
			'level' => $model->levelTitle->message,
			'code' => $model->code,
			'label' => $model::htmlHardDecode($model->title),
            'inode' => true,
		];
        if (!empty($codes)) {
			$data[$model->id] = ArrayHelper::merge($data[$model->id], ['open' => true, 'branch' => $codes]);
        }
		
        if (isset($model->parent)) {
			$data = $this->getParentSchema($model->parent, $data);
        }

		return $data;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function arrayReset($array)
	{
        $array = array_values($array);
        foreach ($array as $key => $val) {
            if (is_array($val) && array_key_exists('branch', $val)) {
                $val = $this->arrayReset($val['branch']);
                $array[$key]['branch'] = $val;
            }
        }

        return $array;
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

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $schema = $this->getArchive();
            $model = ArchivePengolahanSchemaCard::find()
                ->alias('schemaCard')
                ->select(['schemaCard.id', 'schemaCard.card_id', 'schemaCard.schema_id'])
                ->joinWith([
                    'card card', 
                ])
                ->andWhere(['in', 'schemaCard.id', $this->cardsId])
                ->orderBy(['card.from_archive_date_totime' => SORT_ASC])
                ->all();
            $cards = $this->getArchiveCards($model);

            self::updateAll(['archive_json' => Json::encode(ArrayHelper::merge($schema, $cards))], ['id' => $this->id]);
		}
    }
}
