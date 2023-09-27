<?php

class DefaultController extends Controller
{
    public $layout = '//layouts/design3';

	public function actionIndex()
	{
		$this->render('index');
	}
}