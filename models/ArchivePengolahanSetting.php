<?php
/**
 * ArchivePengolahanSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 27 October 2022, 19:17 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models;

use Yii;
use yii\helpers\Json;

class ArchivePengolahanSetting extends \yii\base\Model
{
	use \ommu\traits\UtilityTrait;

	public $app;
	public $license;
	public $permission;
	public $meta_description;
	public $meta_keyword;
	public $breadcrumb_param;
	public $breadcrumb;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['app_type', 'license', 'permission', 'meta_description', 'meta_keyword', 'breadcrumb_param'], 'required'],
			[['permission'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			//[['breadcrumb_param'], 'json'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'app' => Yii::t('app', 'Application ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'breadcrumb_param' => Yii::t('app', 'Breadcrumb Param'),
			'breadcrumb' => Yii::t('app', 'Breadcrumb Apps'),
			'breadcrumb_status' => Yii::t('app', 'Breadcrumb Apps Status'),
			'breadcrumb_app' => Yii::t('app', 'Breadcrumb Apps Name and URL'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$this->license = Yii::$app->setting->get($this->getId('license'), $this->licenseCode());
		$this->permission = Yii::$app->setting->get($this->getId('permission'), 1);
		$this->meta_description = Yii::$app->setting->get($this->getId('meta_description'), 'module pengolahan arsip');
		$this->meta_keyword = Yii::$app->setting->get($this->getId('meta_keyword'), 'pengolahan, arsip, pengolahan arsip');
		$this->breadcrumb_param = Yii::$app->setting->get($this->getId('breadcrumb_param'));

        if ($this->breadcrumb_param == '') {
            $breadcrumb_param = [];
        } else {
            $breadcrumb_param = Json::decode($this->breadcrumb_param);
        }
        if (!empty($breadcrumb_param)) {
            $this->breadcrumb_param = $breadcrumb_param;
        }

		$this->breadcrumb = Yii::$app->setting->get($this->getId('breadcrumb'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId($name)
	{
		return join('_', [$this->app, $name]);
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$moduleName = Yii::t('app', 'Archive pengolahan');
		$module = strtolower(Yii::$app->controller->module->id);
        if (($module = Yii::$app->moduleManager->getModule($module)) != null) {
            $moduleName = strtolower($module->getName());
        }

		$items = array(
			1 => Yii::t('app', 'Yes, the public can view {module} unless they are made private.', ['module' => $moduleName]),
			0 => Yii::t('app', 'No, the public cannot view {module}.', ['module' => $moduleName]),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * function getBreadcrumbStatus
	 */
	public static function getBreadcrumbStatus($value=null)
	{
		$items = array(
			'1' => Yii::t('app', 'Enable'),
			'0' => Yii::t('app', 'Disable'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * function getBreadcrumbApps
	 */
	public function getBreadcrumbApps()
	{
        if (!is_array($this->breadcrumb_param)) {
            return false;
        }
        
        if ($this->breadcrumb_param['status'] != 1) {
            return false;
        }

		// unset($this->breadcrumb_param['status']);
        if (!($this->breadcrumb_param['name'] != '' && $this->breadcrumb_param['url'] != '')) {
            return false;
        }

		return true;
	}

	/**
	 * function getBreadcrumbAppParam
	 */
	public function getBreadcrumbAppParam()
	{
        if (!$this->getBreadcrumbApps()) {
            return [];
        }

		$params = $this->breadcrumb_param;
		unset($params['status']);
		return $params;
	}

	/**
	 * function parseBreadcrumbApps
	 */
	public static function parseBreadcrumbApps($params)
	{
        if (!empty($params)) {
            unset($params['status']);
        }

        if ($params == null) {
            return '-';
        }

		return Html::ul($params, ['encode' => false, 'class' => 'list-boxed']);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if ($this->breadcrumb_param['status'] == '') {
            $this->addError('breadcrumb_param', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('breadcrumb_status')]));
        } else {
            if ($this->breadcrumb_param['status'] == 1 && $this->breadcrumb_param['name'] == '' && $this->breadcrumb_param['url'] == '') {
                $this->addError('breadcrumb_param', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('breadcrumb_app')]));
            }
        }

		if (!empty($this->getErrors())) {
			return false;
        }

		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave()
	{
		if (!$this->beforeValidate()) {
			return false;
        }

		$this->breadcrumb = $this->getBreadcrumbApps() ? 1 : 0;
		$this->breadcrumb_param = Json::encode($this->breadcrumb_param);

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save()
	{
		if (!$this->beforeSave()) {
			return false;
        }

		Yii::$app->setting->set($this->getId('license'), $this->license);
		Yii::$app->setting->set($this->getId('permission'), $this->permission);
		Yii::$app->setting->set($this->getId('meta_description'), $this->meta_description);
		Yii::$app->setting->set($this->getId('meta_keyword'), $this->meta_keyword);
		Yii::$app->setting->set($this->getId('breadcrumb_param'), $this->breadcrumb_param);
		Yii::$app->setting->set($this->getId('breadcrumb'), $this->breadcrumb);
		
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function reset()
	{
		Yii::$app->setting->delete($this->getId('license'));
		Yii::$app->setting->delete($this->getId('permission'));
		Yii::$app->setting->delete($this->getId('meta_description'));
		Yii::$app->setting->delete($this->getId('meta_keyword'));
		Yii::$app->setting->delete($this->getId('breadcrumb_param'));
		Yii::$app->setting->delete($this->getId('breadcrumb'));
	}
}