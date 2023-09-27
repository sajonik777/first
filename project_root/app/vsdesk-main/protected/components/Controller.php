<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/design3';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function init(){
        // import class paths for captcha extended
        Yii::$classMap = array_merge( Yii::$classMap, array(
            'CaptchaExtendedAction' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedAction.php',
            'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedValidator.php'
        ));
        if (empty(constant('redaction'))){
            throw new CHttpException(404, Yii::t('main-ui', '<strong>Warning!</strong> Your license has expired, send email to sales@univef.ru for license renewal!'));
        }
        if((constant('redaction') !== 'DEMO') AND !empty(constant('license_date'))){
        	$expiration = constant('license_date');
        	$date = strtotime('-20 days', strtotime($expiration));
        	$days = date('d', (strtotime($expiration) - strtotime(date('d.m.Y'))));
        	if (strtotime(date('d.m.Y')) >= strtotime($expiration)){
        		Yii::app()->user->setFlash('danger', Yii::t('main-ui', '<strong>Warning!</strong> Your license has expired, send email to sales@univef.ru for license renewal!'));
        	}
        	if (strtotime(date('d.m.Y')) >= strtotime('-1 day', $date) AND strtotime(date('d.m.Y')) < strtotime($expiration)){
        		Yii::app()->user->setFlash('warning', Yii::t('main-ui', '<strong>Warning!</strong> Your license will expire in ') .($days - 1) . Yii::t('main-ui' ,' days, send email to sales@univef.ru for license renewal!'));
        	}
        	if (strtotime(date('d.m.Y')) > strtotime('+10 day', strtotime($expiration))){
        		throw new CHttpException(404, Yii::t('main-ui', '<strong>Warning!</strong> Your license has expired, send email to sales@univef.ru for license renewal!'));
        	}
        }
    }

}
