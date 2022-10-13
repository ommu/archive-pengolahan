<?php
/**
 * ArchivePengolahanPenyerahanJenis
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 12 October 2022, 19:11 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_jenis".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_jenis":
 * @property integer $id
 * @property integer $penyerahan_id
 * @property integer $tag_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $penyerahan
 * @property CoreTags $tag
 * @property Users $creation
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;
use app\models\CoreTags;
use app\models\Users;

class ArchivePengolahanPenyerahanJenis extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	public $tagBody;
	public $penyerahanArsip;
	public $creationDisplayname;
	public $penyerahanTypeId;
	public $penyerahans;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_jenis';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['penyerahan_id', 'tagBody'], 'required'],
			[['penyerahan_id', 'tag_id', 'creation_id'], 'integer'],
			[['tagBody'], 'string'],
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
			'penyerahan_id' => Yii::t('app', 'Penyerahan'),
			'tag_id' => Yii::t('app', 'Jenis Arsip'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'tagBody' => Yii::t('app', 'Jenis Arsip'),
			'penyerahanArsip' => Yii::t('app', 'Penyerahan'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'penyerahanTypeId' => Yii::t('app', 'Tipe Penyerahan'),
			'penyerahans' => Yii::t('app', 'Jumlah Penyerahan'),
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
            ->select(['id', 'type_name'])
            ->via('penyerahan');
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getIsData()
	{
        if ((Yii::$app->request->get('data') && Yii::$app->request->get('data') == 'true') || Yii::$app->request->get('type') || Yii::$app->request->get('penyerahan')) {
            return true;
        }
        return false;
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanJenis the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahanJenis(get_called_class());
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
		$this->templateColumns['tagBody'] = [
			'attribute' => 'tagBody',
			'value' => function($model, $key, $index, $column) {
				return isset($model->tag) ? $model->tag->body : '-';
				// return $model->tagBody;
			},
		];
		$this->templateColumns['penyerahanArsip'] = [
			'attribute' => 'penyerahanArsip',
			'value' => function($model, $key, $index, $column) {
				return isset($model->penyerahan) ? $model->penyerahan->kode_box : '-';
				// return $model->penyerahanArsip;
			},
			'visible' => $this->isData ? (!Yii::$app->request->get('penyerahan') ? true : false) : false,
		];
		$this->templateColumns['penyerahanTypeId'] = [
			'attribute' => 'penyerahanTypeId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->type) ? $model->type->type_name : '-';
				// return $model->typeName;
			},
			'filter' => ArchivePengolahanPenyerahanType::getType(),
			'visible' => $this->isData ? (!Yii::$app->request->get('type') && !Yii::$app->request->get('penyerahan') ? true : false) : false,
		];
		$this->templateColumns['penyerahans'] = [
			'attribute' => 'penyerahans',
			'value' => function($model, $key, $index, $column) {
                $penyerahans = $model->penyerahans;
				return Html::a($penyerahans, ['admin/manage', 'tag' => $model->tag_id], ['title' => Yii::t('app', '{count} penyerahan', ['count' => $penyerahans]), 'data-pjax' => 0]);
			},
			'filter' => false,
            'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !$this->isData,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
			'visible' => $this->isData,
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => $this->isData,
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

		// $this->tagBody = isset($this->tag) ? $this->tag->body : '';
		// $this->penyerahanArsip = isset($this->penyerahan) ? $this->penyerahan->kode_box : '-';
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
                $tagBody = Inflector::slug($this->tagBody);
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
