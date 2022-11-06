<?php
/**
 * Users
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 November 2022, 23:19 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 * This is the model class for table "ommu_users".
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users as UsersModel;

class Users extends UsersModel
{
	public $gridForbiddenColumn = [];

	public $userGroup;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArchiveUsers()
    {
        return $this->hasMany(ArchivePengolahanUsers::className(), ['user_id' => 'user_id'])
            ->alias('users');
    }

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        $this->templateColumns = [];

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
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asEmail($model->email);
			},
			'format' => 'html',
		];
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname;
			},
		];
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
		];
		$this->templateColumns['lastlogin_ip'] = [
			'attribute' => 'lastlogin_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_ip;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['userGroup'] = [
			'attribute' => 'userGroup',
			'value' => function($model, $key, $index, $column) {
                $userGroup = Html::a(Yii::t('app', 'Add Permission'), ['user/admin/create', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Add Permission'), 'class' => 'modal-btn']);
                $archiveUsers = $model->archiveUsers;
                if (is_array($archiveUsers) && !empty($archiveUsers)) {
                    $userGroup = Html::a('<span class="glyphicon glyphicon-ok"></span>', ['user/admin/view', 'id' => $archiveUsers[0]->id], ['title' => Yii::t('app', 'Detail Permission'), 'class' => 'modal-btn']);
                }
				return $userGroup;
			},
			'format' => 'raw',
		];
	}
}
