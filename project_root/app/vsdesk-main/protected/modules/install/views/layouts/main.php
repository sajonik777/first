<!DOCTYPE html>
<html lang="en">
<!-- BEGIN html head -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo Yii::t('install', 'Welcome to installation of Univef'); ?></title>


    <!--RTL-->
</head>
<!-- END html head -->

<body>

<!-- BEGIN wrapper -->

<div class="container">
    <div class="row-fluid">

        <div class="span12">
            <img src="/images/logos/logo-full-main.svg"/>
            <hr>
            <h1><?php echo Yii::t('install', 'Welcome to installation of Univef'); ?></h1>

            <h3><?php echo Yii::t('main-ui', 'Software version ') . constant('version'); ?></h3>
            <div style="float: right; margin-top: -7px;">
            <a href="install?lang=ru"><img src="/images/rus.png"/></a>
            <a href="install?lang=en"><img src="/images/eng.png"/></a>
            </div>
            <?php echo $content; ?>

        </div>
    </div>

    <div class="clear"></div>
    <div class="row">
        <div class="span12">
            <div id="footer">
                <b> <?php echo Yii::t('main-ui', 'Software version ') . constant('version'); ?></b><br/>
            </div>
        </div>
    </div>
    <!-- END footer -->
</div>
</body>

</html>
