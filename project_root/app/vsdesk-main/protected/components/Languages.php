<?php

class Languages extends CApplicationComponent
{
	public function init()
	{
		parent::init();
		$app = Yii::app();
		if (!Yii::app()->user->isGuest){
			$user = CUsers::model()->findByPk($app->user->id);
			if (isset($user->lang))
			{
				$app->language = $user->lang;
			}elseif($app->params['languages']){
				$app->language = $app->params['languages'];
			}
		}else{
			$app->language = $app->params['languages'];
		}
	}


} 
?>