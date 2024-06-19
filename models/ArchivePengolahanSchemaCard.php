<?php
/**
 * ArchivePengolahanSchemaCard
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_schema_card".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_schema_card":
 * @property string $id
 * @property integer $publish
 * @property string $card_id
 * @property string $fond_schema_id
 * @property string $schema_id
 * @property integer $final_id
 * @property integer $fond_id
 * @property integer $archive_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanSchema $schema
 * @property ArchivePengolahanPenyerahanCard $card
 * @property Archives $fond
 * @property Archives $archive
 * @property ArchivePengolahanFinal $final
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Url;
use app\models\Users;
use thamtech\uuid\helpers\UuidHelper;

class ArchivePengolahanSchemaCard extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['schemaTitle', 'cardArchiveDescription', 'finalFondName', 'fondTitle', 'archiveTitle', 'creationDisplayname', 'modifiedDisplayname'];

    public $stayInHere;

	public $fondSchemaTitle;
	public $schemaTitle;
	public $cardArchiveDescription;
	public $finalFondName;
	public $fondTitle;
	public $archiveTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_schema_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'card_id', 'schema_id'], 'required'],
			[['publish', 'final_id', 'fond_id', 'archive_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['fond_schema_id', 'final_id', 'fond_id', 'archive_id', 'stayInHere'], 'safe'],
			[['id', 'card_id', 'fond_schema_id', 'schema_id'], 'string', 'max' => 36],
			[['id'], 'unique'],
			[['fond_schema_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanSchema::className(), 'targetAttribute' => ['fond_schema_id' => 'id']],
			[['schema_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanSchema::className(), 'targetAttribute' => ['schema_id' => 'id']],
			[['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahanCard::className(), 'targetAttribute' => ['card_id' => 'id']],
			[['fond_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['fond_id' => 'id']],
			[['archive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['archive_id' => 'id']],
			[['final_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanFinal::className(), 'targetAttribute' => ['final_id' => 'id']],
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
			'card_id' => Yii::t('app', 'Card'),
			'fond_schema_id' => Yii::t('app', 'Fond Schema'),
			'schema_id' => Yii::t('app', 'Schema'),
			'final_id' => Yii::t('app', 'Final'),
			'fond_id' => Yii::t('app', 'Fond'),
			'archive_id' => Yii::t('app', 'Archive'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'fondSchemaTitle' => Yii::t('app', 'Fond Schema'),
			'schemaTitle' => Yii::t('app', 'Schema'),
			'cardArchiveDescription' => Yii::t('app', 'Card'),
			'finalFondName' => Yii::t('app', 'Final'),
			'fondTitle' => Yii::t('app', 'Fond'),
			'archiveTitle' => Yii::t('app', 'Archive'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	public function getFondSchema()
	{
		return $this->hasOne(ArchivePengolahanSchema::className(), ['id' => 'fond_schema_id'])
            ->select(['id', 'code', 'title']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSchema()
	{
		return $this->hasOne(ArchivePengolahanSchema::className(), ['id' => 'schema_id'])
            ->select(['id', 'parent_id', 'level_id', 'code', 'title']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFond()
	{
		return $this->hasOne(Archives::className(), ['id' => 'fond_id']);
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
	public function getFinal()
	{
		return $this->hasOne(ArchivePengolahanFinal::className(), ['id' => 'final_id']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanSchemaCard the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanSchemaCard(get_called_class());
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
				return isset($model->card) ? $model->card->penyerahan->type->type_name : '-';
				// return $model->cardArchiveDescription;
			},
			'visible' => !Yii::$app->request->get('card') ? true : false,
		];
		$this->templateColumns['fondSchemaTitle'] = [
			'attribute' => 'fondSchemaTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->fondSchema) ? $model->fondSchema->title : '-';
				// return $model->fondSchemaTitle;
			},
			'visible' => !Yii::$app->request->get('schema') ? true : false,
		];
		$this->templateColumns['schemaTitle'] = [
			'attribute' => 'schemaTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->schema) ? $model->schema->title : '-';
				// return $model->schemaTitle;
			},
			'visible' => !Yii::$app->request->get('schema') ? true : false,
		];
		$this->templateColumns['finalFondName'] = [
			'attribute' => 'finalFondName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->final) ? $model->final->fond_name : '-';
				// return $model->finalFondName;
			},
			'visible' => !Yii::$app->request->get('final') ? true : false,
		];
		$this->templateColumns['fondTitle'] = [
			'attribute' => 'fondTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->fond) ? $model->fond->title : '-';
				// return $model->fondTitle;
			},
			'visible' => !Yii::$app->request->get('fond') ? true : false,
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

		// $this->cardArchiveDescription = isset($this->card) ? $this->card->archive_description : '-';
		// $this->fondSchemaTitle = isset($this->fondSchema) ? $this->fondSchema->title : '-';
		// $this->schemaTitle = isset($this->schema) ? $this->schema->title : '-';
		// $this->finalFondName = isset($this->final) ? $this->final->fond_name : '-';
		// $this->fondTitle = isset($this->fond) ? $this->fond->title : '-';
		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
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
