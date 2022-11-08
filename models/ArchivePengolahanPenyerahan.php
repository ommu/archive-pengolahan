<?php
/**
 * ArchivePengolahanPenyerahan
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:31 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_penyerahan".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_penyerahan":
 * @property integer $id
 * @property integer $publish
 * @property integer $type_id
 * @property string $kode_box
 * @property string $pencipta_arsip
 * @property string $tahun
 * @property string $nomor_arsip
 * @property string $jumlah_arsip
 * @property string $nomor_box
 * @property string $jumlah_box
 * @property string $nomor_box_urutan
 * @property string $lokasi
 * @property string $color_code
 * @property string $description
 * @property string $publication_file
 * @property integer $pengolahan_status
 * @property string $pengolahan_tahun
 * @property integer $import_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchivePengolahanPenyerahanType $type
 * @property ArchivePengolahanPenyerahanCard[] $cards
 * @property ArchivePengolahanPenyerahanGrid $grid
 * @property ArchivePengolahanPenyerahanItem[] $items
 * @property ArchivePengolahanPenyerahanJenis[] $jenis
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\Users;
use yii\base\Event;
use yii\helpers\Inflector;

class ArchivePengolahanPenyerahan extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

    public $gridForbiddenColumn = ['jumlah_arsip', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'color_code', 'description', 'pengolahan_tahun', 'creation_date', 'modified_date', 'updated_date', 
        'jenisArsip', 'typeName', 'creationDisplayname', 'modifiedDisplayname', 'creator'];

    public $stayInHere;
	public $creator;
	public $jenisArsip;
	public $old_publication_file;

	public $typeName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $jenisId;
	public $oPublication;
	public $oCard;
    public $oItem;

	const SCENARIO_PENGOLAHAN_STATUS = 'pengolahanStatusForm';
	const SCENARIO_PUBLICATION = 'publicationForm';
	const EVENT_BEFORE_SAVE_PENYERAHAN = 'BeforeSavePenyerahan';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_penyerahan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type_id', 'kode_box', 'pencipta_arsip'], 'required'],
			[['pengolahan_status', 'pengolahan_tahun'], 'required', 'on' => self::SCENARIO_PENGOLAHAN_STATUS],
			[['publish', 'type_id', 'pengolahan_status', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['kode_box', 'pencipta_arsip', 'nomor_arsip', 'jumlah_arsip', 'nomor_box', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'description'], 'string'],
			[['tahun', 'nomor_arsip', 'jumlah_arsip', 'nomor_box', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'color_code', 'description', 'publication_file', 'pengolahan_status', 'pengolahan_tahun', 'stayInHere', 'jenisArsip', 'creator'], 'safe'],
			[['kode_box'], 'string', 'max' => 64],
			[['tahun', 'pengolahan_tahun', 'color_code'], 'string', 'max' => 32],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchivePengolahanPenyerahanType::className(), 'targetAttribute' => ['type_id' => 'id']],
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
			'type_id' => Yii::t('app', 'Type'),
			'kode_box' => Yii::t('app', 'Kode Box'),
			'pencipta_arsip' => Yii::t('app', 'Pencipta Arsip'),
			'tahun' => Yii::t('app', 'Tahun'),
			'nomor_arsip' => Yii::t('app', 'Nomor Arsip'),
			'jumlah_arsip' => Yii::t('app', 'Jumlah Arsip'),
			'nomor_box' => Yii::t('app', 'Nomor Box'),
			'jumlah_box' => Yii::t('app', 'Jumlah Box'),
			'nomor_box_urutan' => Yii::t('app', 'Nomor Box Dari Keseluruhan'),
			'lokasi' => Yii::t('app', 'Lokasi'),
			'pengolahan_status' => Yii::t('app', 'Status Pengolahan'),
			'pengolahan_tahun' => Yii::t('app', 'Tahun Pengolahan'),
			'color_code' => Yii::t('app', 'Color Code'),
			'description' => Yii::t('app', 'Description'),
			'publication_file' => Yii::t('app', 'Publication File'),
			'import_id' => Yii::t('app', 'Import'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'creator' => Yii::t('app', 'Name of creator(s)'),
			'jenisArsip' => Yii::t('app', 'Jenis Arsip'),
			'old_publication_file' => Yii::t('app', 'Old Publication File'),
			'typeName' => Yii::t('app', 'Type'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oPublication' => Yii::t('app', 'Publication File'),
			'oCard' => Yii::t('app', 'Cards'),
			'oItem' => Yii::t('app', 'Items'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_PENGOLAHAN_STATUS] = ['publish', 'type_id', 'kode_box', 'pencipta_arsip', 'tahun', 'nomor_arsip', 'jumlah_arsip', 'nomor_box', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'color_code', 'description', 'pengolahan_status', 'pengolahan_tahun', 'stayInHere'];
		$scenarios[self::SCENARIO_PUBLICATION] = ['publish', 'type_id', 'kode_box', 'pencipta_arsip', 'tahun', 'nomor_arsip', 'jumlah_arsip', 'nomor_box', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'color_code', 'description', 'publication_file', 'pengolahan_status', 'pengolahan_tahun', 'stayInHere'];
		return $scenarios;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(ArchivePengolahanPenyerahanType::className(), ['id' => 'type_id'])
            ->select(['id', 'type_name', 'feature']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCards($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArchivePengolahanPenyerahanCard::className(), ['penyerahan_id' => 'id'])
				->alias('cards')
				->andOnCondition([sprintf('%s.publish', 'cards') => $publish]);
        }

		$model = ArchivePengolahanPenyerahanCard::find()
            ->alias('t')
            ->where(['t.penyerahan_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$cards = $model->count();

		return $cards ? $cards : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGrid()
	{
		return $this->hasOne(ArchivePengolahanPenyerahanGrid::className(), ['id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems($count=false, $publish=1)
    {
        if ($count == false) {
            return $this->hasMany(ArchivePengolahanPenyerahanItem::className(), ['penyerahan_id' => 'id'])
                ->alias('items')
                ->andOnCondition([sprintf('%s.publish', 'items') => $publish]);
        }

        $model = ArchivePengolahanPenyerahanItem::find()
            ->alias('t')
            ->where(['t.penyerahan_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
        $items = $model->count();

        return $items ? $items : 0;
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getJenis($relation=true, $val='id')
	{
        if ($relation == false) {
            return \yii\helpers\ArrayHelper::map($this->jenis, 'tag_id', $val == 'id' ? 'id' : 'tag.body');
        }

        return $this->hasMany(ArchivePengolahanPenyerahanJenis::className(), ['penyerahan_id' => 'id'])
            ->select(['id', 'penyerahan_id', 'tag_id']);
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
	public function getCreators($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->creators, 'creator_id', $val=='id' ? 'id' : 'creator.creator_name');
        }

		return $this->hasMany(ArchivePengolahanPenyerahanCreator::className(), ['penyerahan_id' => 'id'])
            ->select(['id', 'penyerahan_id', 'creator_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahan the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanPenyerahan(get_called_class());
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
		$this->templateColumns['type_id'] = [
			'attribute' => 'type_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->type) ? $model->type->type_name : '-';
				// return $model->typeName;
			},
			'filter' => ArchivePengolahanPenyerahanType::getType(),
			'visible' => !Yii::$app->request->get('type') ? true : false,
		];
		$this->templateColumns['kode_box'] = [
			'attribute' => 'kode_box',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->kode_box);
			},
			'format' => 'html',
		];
		$this->templateColumns['creator'] = [
			'attribute' => 'creator',
			'label' => Yii::t('app', 'Creator'),
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getCreators(true, 'title'));
			},
			'format' => 'html',
		];
		$this->templateColumns['pencipta_arsip'] = [
			'attribute' => 'pencipta_arsip',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->pencipta_arsip);
			},
			'format' => 'html',
		];
		$this->templateColumns['tahun'] = [
			'attribute' => 'tahun',
			'value' => function($model, $key, $index, $column) {
				return $model->tahun;
			},
		];
		$this->templateColumns['nomor_arsip'] = [
			'attribute' => 'nomor_arsip',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->nomor_arsip);
			},
			'format' => 'html',
		];
		$this->templateColumns['jumlah_arsip'] = [
			'attribute' => 'jumlah_arsip',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->jumlah_arsip);
			},
			'format' => 'html',
		];
		$this->templateColumns['nomor_box'] = [
			'attribute' => 'nomor_box',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->nomor_box);
			},
			'format' => 'html',
		];
		$this->templateColumns['jumlah_box'] = [
			'attribute' => 'jumlah_box',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->jumlah_box);
			},
			'format' => 'html',
		];
		$this->templateColumns['nomor_box_urutan'] = [
			'attribute' => 'nomor_box_urutan',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->nomor_box_urutan);
			},
			'format' => 'html',
		];
		$this->templateColumns['lokasi'] = [
			'attribute' => 'lokasi',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->lokasi);
			},
			'format' => 'html',
		];
		$this->templateColumns['jenisArsip'] = [
			'attribute' => 'jenisArsip',
			'value' => function($model, $key, $index, $column) {
				return self::parseJenisArsip($model->getJenis(false, 'title'), 'jenis', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['color_code'] = [
			'attribute' => 'color_code',
			'value' => function($model, $key, $index, $column) {
				return $model->color_code;
			},
		];
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
				return nl2br($model->description);
			},
			'format' => 'html',
		];
		$this->templateColumns['pengolahan_tahun'] = [
			'attribute' => 'pengolahan_tahun',
			'value' => function($model, $key, $index, $column) {
				return $model->pengolahan_tahun;
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
		$this->templateColumns['oPublication'] = [
			'attribute' => 'oPublication',
			'value' => function($model, $key, $index, $column) {
                if (!empty($model->type->feature) && in_array('publication', $model->type->feature)) {
                    return Html::a($model->oPublication ? '<span class="glyphicon glyphicon-ok"></span>' : Yii::t('app', 'Upload'), ['publication', 'id' => $model->primaryKey], ['title' => $model->oPublication ? Yii::t('app', 'View Publication File') : Yii::t('app', 'Upload Publication File'), 'data-pjax' => 0]);
                }
				return '-';
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['pengolahan_status'] = [
			'attribute' => 'pengolahan_status',
			'value' => function($model, $key, $index, $column) {
                $pengolahanStatus = $this->filterYesNo($model->pengolahan_status);
				return Html::a($pengolahanStatus, ['status', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update Status Pengolahan'), 'class' => 'modal-btn']);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['oItem'] = [
			'attribute' => 'oItem',
			'value' => function($model, $key, $index, $column) {
				// $items = $model->getItems(true);
				$items = $model->grid->item;
				return Html::a($items, ['penyerahan/item/manage', 'penyerahan' => $model->primaryKey], ['title' => Yii::t('app', '{count} items', ['count' => $items]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oCard'] = [
			'attribute' => 'oCard',
			'value' => function($model, $key, $index, $column) {
				// $cards = $model->getCards(true);
				$cards = $model->grid->card;
				return Html::a($cards, ['penyerahan/card/manage', 'penyerahan' => $model->primaryKey], ['title' => Yii::t('app', '{count} cards', ['count' => $cards]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
	 * function parseJenisArsip
	 */
	public static function parseJenisArsip($subjects, $attr='subjectId', $sep='li')
	{
        if (!is_array($subjects) || (is_array($subjects) && empty($subjects))) {
            return '-';
        }

		$items = [];
		foreach ($subjects as $key => $val) {
			$items[$val] = Html::a($val, ['penyerahan/admin/manage', $attr => $key], ['title' => $val]);
		}

        if ($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class' => 'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * function parsePenyerahan
	 */
	public static function parsePenyerahan($model, $urlTitle=true)
	{
		$title = $model->pencipta_arsip;
        $penyerahanTitle = $urlTitle == true ? Html::a($title, ['penyerahan/admin/view', 'id' => $model->id], ['title' => $title, 'class' => 'modal-btn']) : $title ;

        $html = Html::button($model->kode_box, ['class' => 'btn btn-info btn-xs']).'<br/>';
        $html .= $penyerahanTitle;

		return $html;
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@public/archive/penyerahan') : 'archive/penyerahan');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_publication_file = $this->publication_file;
        $this->oPublication = $this->publication_file ? 1 : 0;
		// $this->typeName = isset($this->type) ? $this->type->type_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->card = $this->getCards(true) ? 1 : 0;
        // $this->item = $this->getItems(true) ? 1 : 0;
		// $this->jenisArsip = $this->getJenis(true) ? 1 : 0;
        $this->pengolahan_status = $this->pengolahan_status != '' && $this->pengolahan_status == 1 ? $this->pengolahan_status : 0;
		// $this->oCard = isset($this->grid) ? $this->grid->card : 0;
		// $this->oItem = isset($this->grid) ? $this->grid->item : 0;
		// $this->creator = implode(',', $this->getCreators(true, 'title'));
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->scenario == self::SCENARIO_PUBLICATION) {
                // $this->publication_file = UploadedFile::getInstance($this, 'publication_file');
                if ($this->publication_file instanceof UploadedFile && !$this->publication_file->getHasError()) {
                    $publicationFileFileType = ['pdf'];
                    if (!in_array(strtolower($this->publication_file->getExtension()), $publicationFileFileType)) {
                        $this->addError('publication_file', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
                            'name' => $this->publication_file->name,
                            'extensions' => $this->formatFileType($publicationFileFileType, false),
                        ]));
                    }
                } else {
                    if ($this->isNewRecord || (!$this->isNewRecord && $this->old_publication_file == '')) {
                        $this->addError('publication_file', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('publication_file')]));
                    }
                }
            }

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
            if (!$insert) {
                // set jenisArsip
                $event = new Event(['sender' => $this]);
                Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_PENYERAHAN, $event);

                $uploadPath = self::getUploadPath();
                $verwijderenPath = join('/', [$uploadPath, 'verwijderen']);
                $this->createUploadDirectory($uploadPath);

                // $this->publication_file = UploadedFile::getInstance($this, 'publication_file');
                if ($this->publication_file instanceof UploadedFile && !$this->publication_file->getHasError()) {
                    $fileName = join('_', [Inflector::camelize($this->type->type_name), time(), $this->id, $this->kode_box]).'.'.strtolower($this->publication_file->getExtension()); 
                    if ($this->publication_file->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_publication_file != '' && file_exists(join('/', [$uploadPath, $this->old_publication_file]))) {
                            rename(join('/', [$uploadPath, $this->old_publication_file]), join('/', [$verwijderenPath, join('_', [$this->old_publication_file, time(), 'change'])]));
                        }
                        $this->publication_file = $fileName;
                    }
                } else {
                    if ($this->publication_file == '') {
                        $this->publication_file = $this->old_publication_file;
                    }
                }
            }
            $this->color_code = strtolower($this->color_code);
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        $uploadPath = self::getUploadPath();
        $verwijderenPath = join('/', [$uploadPath, 'verwijderen']);
        $this->createUploadDirectory($uploadPath);

        if ($insert) {
			// set jenisArsip
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_PENYERAHAN, $event);

            // $this->publication_file = UploadedFile::getInstance($this, 'publication_file');
            if ($this->publication_file instanceof UploadedFile && !$this->publication_file->getHasError()) {
                $fileName = join('_', [Inflector::camelize($this->type->type_name), time(), $this->id, $this->kode_box]).'.'.strtolower($this->publication_file->getExtension()); 
                if ($this->publication_file->saveAs(join('/', [$uploadPath, $fileName]))) {
                    self::updateAll(['publication_file' => $fileName], ['id' => $this->id]);
                }
            }
		}
    }

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
        parent::afterDelete();

		$uploadPath = self::getUploadPath();
		$verwijderenPath = join('/', [$uploadPath, 'verwijderen']);

        if ($this->publication_file != '' && file_exists(join('/', [$uploadPath, $this->publication_file]))) {
            rename(join('/', [$uploadPath, $this->publication_file]), join('/', [$verwijderenPath, join('_', [$this->publication_file, time(), 'deleted'])]));
        }

	}
}
