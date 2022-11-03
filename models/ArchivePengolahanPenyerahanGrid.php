<?php
/**
 * ArchivePengolahanPenyerahanGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 3 November 2022, 22:39 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan_grid".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan_grid":
 * @property integer $id
 * @property integer $card
 * @property integer $item
 * @property integer $jenis
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahan $0
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;

class ArchivePengolahanPenyerahanGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan_grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'card', 'item', 'jenis'], 'required'],
			[['id', 'card', 'item', 'jenis'], 'integer'],
			[['id'], 'unique'],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahan::className(), 'targetAttribute' => ['id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'card' => Yii::t('app', 'Card'),
			'item' => Yii::t('app', 'Item'),
			'jenis' => Yii::t('app', 'Jenis'),
			'modified_date' => Yii::t('app', 'Modified Date'),
		];
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
}
