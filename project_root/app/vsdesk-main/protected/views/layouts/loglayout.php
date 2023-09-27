<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" type="text/css" href="<?php
        echo Yii::app()->request->baseUrl; ?>/css/print.css"
			  media="print"/>
		<!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php
        echo Yii::app()->request->baseUrl; ?>/css/ie.css"
          media="screen, projection"/>
      <![endif]-->
		<link rel="icon" href="/images/icons/favicon.ico" type="image/x-icon">
		<link rel="shortcut icon" href="/images/icons/favicon.ico" type="image/x-icon">
		<script src="https://kit.fontawesome.com/1c8c98423f.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="<?php
        echo Yii::app()->request->baseUrl; ?>/css/form.css"/>
		<title><?php
            if (!empty(Yii::app()->params['pageHeader'])) {
                echo Yii::app()->params['pageHeader'];
            } else {
                echo Yii::t('main-ui', 'Univef Service Desk system');
            } ?></title>

		<!-- Theme style -->
		<link rel="stylesheet" href="/css/AdminLTE.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="/css/skins/_all-skins.min.css">
		<link rel="stylesheet" href="/css/skins/uvf/main.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="/images/logos/logo-full-main.svg" alt="logo">
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg"><?php
                    echo Yii::app()->params->loginText; ?></p>
                <?php
                echo $content; ?>

			</div><!-- /.login-box-body -->
		</div><!-- /.login-box -->

	</body>
</html>
