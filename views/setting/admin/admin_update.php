<?php
/**
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\setting\AdminController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 29 October 2022, 19:08 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
?>

<div class="archive-setting-update">

    <div class="archive-setting-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal form-label-left'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        //'enableClientScript' => true,
        'fieldConfig' => [
            'errorOptions' => [
                'encode' => false,
            ],
        ],
    ]); ?>

    <?php //echo $form->errorSummary($model);?>

    <?php
    if (!$model->getErrors()) {
        $model->license = $model->licenseCode();
    }
    echo $form->field($model, 'license')
        ->textInput(['maxlength' => true])
        ->label($model->getAttributeLabel('license'))
        ->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

    <?php $permission = $model::getPermission();
    echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
        ->radioList($permission)
        ->label($model->getAttributeLabel('permission'))
        ->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

    <?php echo $form->field($model, 'meta_description')
        ->textarea(['rows' => 6, 'cols' => 50])
        ->label($model->getAttributeLabel('meta_description')); ?>

    <?php echo $form->field($model, 'meta_keyword')
        ->textarea(['rows' => 6, 'cols' => 50])
        ->label($model->getAttributeLabel('meta_keyword')); ?>

    <hr/>

    <?php $breadcrumbAppsName = $form->field($model, 'breadcrumb_param[name]', ['template' => '{beginWrapper}<div class="h6 mt-0 mb-4">App Name</div>{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-4 col-xs-6 col-sm-offset-3 mt-4'], 'options' => ['tag' => null]])
        ->label($model->getAttributeLabel('breadcrumb_param[name]')); ?>

    <?php $breadcrumbAppsUrl = $form->field($model, 'breadcrumb_param[url]', ['template' => '{beginWrapper}<div class="h6 mt-0 mb-4">App URL</div>{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-5 col-xs-6 mt-4'], 'options' => ['tag' => null]])
        ->label($model->getAttributeLabel('breadcrumb_param[url]')); ?>

    <?php $status = $model::getBreadcrumbStatus();
    echo $form->field($model, 'breadcrumb_param[status]', ['template' => '{label}{beginWrapper}<div class="h6 mt-4 mb-4">Status</div>{input}{endWrapper}'.$breadcrumbAppsName.$breadcrumbAppsUrl.'{error}{hint}', 'horizontalCssClasses' => ['error' => 'col-sm-6 col-xs-12 col-sm-offset-3', 'hint' => 'col-sm-6 col-xs-12 col-sm-offset-3']])
        ->dropDownList($status, ['prompt' => ''])
        ->label($model->getAttributeLabel('breadcrumb')); ?>

    <hr/>

    <?php echo $form->field($model, 'submitButton')
        ->submitButton(['button' => Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary'])]); ?>

    <?php ActiveForm::end(); ?>

    </div>

</div>