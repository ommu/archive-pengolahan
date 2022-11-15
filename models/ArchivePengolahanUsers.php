<?php
/**
 * ArchivePengolahanUsers
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 10:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_archive_pengolahan_users".
 *
 * The followings are the available columns in table "ommu_archive_pengolahan_users":
 * @property integer $id
 * @property integer $publish
 * @property integer $user_id
 * @property string $user_code
 * @property string $groups
 * @property integer $archives
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\Users;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use mdm\admin\models\Assignment;

class ArchivePengolahanUsers extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['modified_date', 'updated_date', 'creationDisplayname', 'modifiedDisplayname'];

	public $stayInHere;

	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_pengolahan_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id', 'user_code'], 'required'],
			[['publish', 'user_id', 'archives', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			//[['groups'], 'json'],
			[['groups', 'stayInHere'], 'safe'],
			[['user_code'], 'string', 'max' => 8],
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
			'user_id' => Yii::t('app', 'User'),
			'user_code' => Yii::t('app', 'User Code'),
			'groups' => Yii::t('app', 'Groups'),
			'archives' => Yii::t('app', 'Archives'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'email', 'displayname']);
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
	 * @return \ommu\archivePengolahan\models\query\ArchivePengolahanUsers the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archivePengolahan\models\query\ArchivePengolahanUsers(get_called_class());
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
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
                return $model::parseUser($model, false);
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['user_code'] = [
			'attribute' => 'user_code',
			'value' => function($model, $key, $index, $column) {
				return $model->user_code;
			},
		];
		$this->templateColumns['groups'] = [
			'attribute' => 'groups',
			'value' => function($model, $key, $index, $column) {
                if (is_array($model->groups) && empty($model->groups)) {
                    return '-';
                }
                return Json::encode($model->groups);
			},
		];
		$this->templateColumns['archives'] = [
			'attribute' => 'archives',
			'value' => function($model, $key, $index, $column) {
				return $model->archives;
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
	 * function parseUser
	 */
	public static function parseUser($user, $userCode=true) 
	{
        if ($user) {
            $data = [];

            $user->user->displayname ? array_push($data, $user->user->displayname) : '';
            $user->user->email ? array_push($data, $user->user->email) : '';
            if ($userCode) {
                $user->user_code ? array_push($data, $user->user_code) : '';
            }

            return implode(' / ', $data);
        }

        return $user;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

        if ($this->groups == '') {
            $this->groups = [];
        } else {
            $this->groups = Json::decode($this->groups);
        }
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
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
                $user = self::find()->where(['publish' => 1, 'user_id' => $this->user_id])->one();
                if ($user != null) {
                    $this->addError('user_id', Yii::t('app', 'User is already registered'));
                }
    
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
			$this->user_code = strtoupper($this->user_code);
			$this->groups = Json::encode($this->groups);
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        $groups = Json::decode($this->groups);

        if ($insert) {
            if (!empty($groups)) {
                $model = new Assignment($this->user_id);
                $model->assign($groups);
            }

		} else {
            if (array_key_exists('groups', $changedAttributes) && $changedAttributes['groups'] != $this->groups) {
                $oldGroups = Json::decode($changedAttributes['groups']);
                if (!is_array($oldGroups)) {
                    $oldGroups = [];
                }

                $model = new Assignment($this->user_id);

                if (!empty($groups)) {
                    foreach ($groups as $group) {
                        if (in_array($group, $oldGroups)) {
                            unset($oldGroups[array_keys($oldGroups, $group)[0]]);
                            continue;
                        }
                        $model->assign([$group]);
                    }
                }

                if (!empty($oldGroups)) {
                    $model->revoke($oldGroups);
                }
            }
        }
    }
}
