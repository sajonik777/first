<?php

$rcreate = array(
    'label' => Yii::t('main-ui', 'Create ticket'),
    'icon' => 'fa-solid fa-plus',
    'url' => '/request/create',
    false
);
$pcreate = array(
    'label' => Yii::t('main-ui', 'Problem by incident'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/problems/create')
);
$pcreateh = array(
    'label' => Yii::t('main-ui', 'Problem by asset'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/problems/createh')
);
$acreate = array('label' => Yii::t('main-ui', 'Create asset'), 'icon' => 'hdd', 'url' => array('/asset/create'));
$aacreate = array(
    'label' => Yii::t('main-ui', 'Create new asset type'),
    'icon' => 'hdd',
    'url' => array('/assetAttrib/create')
);
$ucreate = array('label' => Yii::t('main-ui', 'Create unit'), 'icon' => 'hdd', 'url' => array('/cunits/create'));
$utcreate = array(
    'label' => Yii::t('main-ui', 'Create new unit type'),
    'icon' => 'hdd',
    'url' => array('/cunitTypes/create')
);
$screate = array(
    'label' => Yii::t('main-ui', 'Create service'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/service/create')
);
$slacreate = array(
    'label' => Yii::t('main-ui', 'Create service level'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/sla/create')
);
$kcreate = array(
    'label' => Yii::t('main-ui', 'Create new record'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/knowledge/module/create')
);
$kccreate = array(
    'label' => Yii::t('main-ui', 'Create new category'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/knowledge/category/create')
);
$ncreate = array(
    'label' => Yii::t('main-ui', 'Create news'),
    'icon' => 'fa-solid fa-plus',
    'url' => array('/news/module/create')
);
$tickets = array('label' => Yii::t('main-ui', 'Tickets'), 'icon' => 'edit', 'url' => array('/request/index'));
$kbase = array(
    'label' => Yii::t('main-ui', 'Knowledgebase'),
    'icon' => 'book',
    'url' => array('/knowledge/module/index')
);
$myaccount = array(
    'label' => Yii::t('main-ui', 'My account'),
    'icon' => 'user',
    'url' => array('/cusers/' . Yii::app()->user->id)
);
$login = array(
    'label' => Yii::t('main-ui', 'Login'),
    'url' => array('/site/login'),
    'visible' => Yii::app()->user->isGuest
);
$logout = array(
    'label' => Yii::t('main-ui', 'Logout') . ' (' . Yii::app()->user->name . ')',
    'icon' => 'signout',
    'url' => array('/site/logout'),
    'visible' => !Yii::app()->user->isGuest
);
$create = array_filter(array(
    Yii::app()->user->checkAccess('createRequest') ? array('label' => Yii::t('main-ui', 'Tickets')) : null,
    Yii::app()->user->checkAccess('createRequest') ? $rcreate : null,
    Yii::app()->user->checkAccess('createProblem') ? array('label' => Yii::t('main-ui', 'Problems')) : null,
    Yii::app()->user->checkAccess('createProblem') ? $pcreate : null,
    Yii::app()->user->checkAccess('createProblem') ? $pcreateh : null,
    (Yii::app()->user->checkAccess('createAsset') OR Yii::app()->user->checkAccess('createAssetType') OR Yii::app()->user->checkAccess('createUnit') OR Yii::app()->user->checkAccess('createUnitType')) ? array(
        'label' => Yii::t('main-ui', 'Assets')
    ) : null,
    Yii::app()->user->checkAccess('createAsset') ? $acreate : null,
    Yii::app()->user->checkAccess('createAssetType') ? $aacreate : null,
    Yii::app()->user->checkAccess('createUnit') ? $ucreate : null,
    Yii::app()->user->checkAccess('createUnitType') ? $utcreate : null,
    (Yii::app()->user->checkAccess('createService') OR Yii::app()->user->checkAccess('createSla')) ? array(
        'label' => Yii::t('main-ui', 'Services')
    ) : null,
    Yii::app()->user->checkAccess('createService') ? $screate : null,
    Yii::app()->user->checkAccess('createSla') ? $slacreate : null,
    (Yii::app()->user->checkAccess('createKB') OR Yii::app()->user->checkAccess('createKBCat')) ? array(
        'label' => Yii::t('main-ui', 'Knowledgebase')
    ) : null,
    Yii::app()->user->checkAccess('createKB') ? $kcreate : null,
    Yii::app()->user->checkAccess('createKBCat') ? $kccreate : null,
    Yii::app()->user->checkAccess('createNews') ? $ncreate : null,
));
$references = array_filter(array(
    Yii::app()->user->checkAccess('listUser') ? array(
        'label' => Yii::t('main-ui', 'Users'),
        'icon' => 'icon-user',
        'url' => array('/cusers')
    ) : null,
    Yii::app()->user->checkAccess('listCompany') ? array(
        'label' => Yii::t('main-ui', 'Companies'),
        'icon' => 'icon-group',
        'url' => array('/companies')
    ) : null,
    //Yii::app()->user->checkAccess('listCompany') ? array('label' => Yii::t('main-ui', 'Contractors'), 'icon' => 'icon-group', 'url' => array('/contractors')) : NULL,
    Yii::app()->user->checkAccess('listDepart') ? array(
        'label' => Yii::t('main-ui', 'Departments'),
        'icon' => 'icon-group',
        'url' => array('/depart')
    ) : null,
    Yii::app()->user->checkAccess('listGroup') ? array(
        'label' => Yii::t('main-ui', 'Groups'),
        'icon' => 'icon-group',
        'url' => array('/groups')
    ) : null,
    //Yii::app()->user->checkAccess('listPService') ? array('label' => Yii::t('main-ui', 'Parent Services'), 'icon' => 'folder-open', 'url' => array('/parentService/index')) : NULL,
    Yii::app()->user->checkAccess('listService') ? array(
        'label' => Yii::t('main-ui', 'Services'),
        'icon' => 'folder-open',
        'url' => array('/service/index')
    ) : null,
    Yii::app()->user->checkAccess('listSla') ? array(
        'label' => Yii::t('main-ui', 'Service level'),
        'icon' => 'bar-chart',
        'url' => array('/sla/index')
    ) : null,
    Yii::app()->user->checkAccess('listPriority') ? array(
        'label' => Yii::t('main-ui', 'Ticket Priority'),
        'icon' => 'retweet',
        'url' => array('/zpriority/index')
    ) : null,
    Yii::app()->user->checkAccess('listAssetType') ? array(
        'label' => Yii::t('main-ui', 'Asset types'),
        'icon' => 'sitemap',
        'url' => array('/assetAttrib/index')
    ) : null,
    Yii::app()->user->checkAccess('listUnitType') ? array(
        'label' => Yii::t('main-ui', 'Unit type'),
        'icon' => 'sitemap',
        'url' => array('/cunitTypes/index')
    ) : null,
    Yii::app()->user->checkAccess('listKB') ? array(
        'label' => Yii::t('main-ui', 'Knowledgebase'),
        'icon' => 'book',
        'url' => array('/knowledge/module/index')
    ) : null,
    Yii::app()->user->checkAccess('listKBCat') ? array(
        'label' => Yii::t('main-ui', 'Knowledgebase cats'),
        'icon' => 'book',
        'url' => array('/knowledge/category')
    ) : null,
    Yii::app()->user->checkAccess('listNews') ? array(
        'label' => Yii::t('main-ui', 'News'),
        'icon' => 'exclamation-sign',
        'url' => array('/news/module/index')
    ) : null,
    Yii::app()->user->checkAccess('listStatus') ? array(
        'label' => Yii::t('main-ui', 'Statuses'),
        'icon' => 'icon-table',
        'url' => array('/status')
    ) : null,
    Yii::app()->user->checkAccess('listCategory') ? array(
        'label' => Yii::t('main-ui', 'Request categories'),
        'icon' => 'icon-table',
        'url' => array('/category')
    ) : null,
    Yii::app()->user->checkAccess('listETemplate') ? array(
        'label' => Yii::t('main-ui', 'E-mail templates'),
        'icon' => 'icon-edit',
        'url' => array('/messages')
    ) : null,
    Yii::app()->user->checkAccess('listSTemplate') ? array(
        'label' => Yii::t('main-ui', 'SMS templates'),
        'icon' => 'icon-edit',
        'url' => array('/smss')
    ) : null,
    Yii::app()->user->checkAccess('listTemplates') ? array(
        'label' => Yii::t('main-ui', 'Reply templates'),
        'icon' => 'icon-edit',
        'url' => array('/replytemplates')
    ) : null,
    Yii::app()->user->checkAccess('listUnitTemplates') ? array(
        'label' => Yii::t('main-ui', 'Print form templates'),
        'icon' => 'icon-edit',
        'url' => array('/unittemplates')
    ) : null,
    Yii::app()->user->checkAccess('listFieldsets') ? array(
        'label' => Yii::t('main-ui', 'Fieldsets'),
        'icon' => 'icon-list',
        'url' => array('/fieldsets')
    ) : null,
));
$reports = array_filter(array(
    Yii::app()->user->checkAccess('companiesReport') ? array(
        'label' => Yii::t('main-ui', 'Companies report'),
        'icon' => 'file',
        'url' => array('/report/companies')
    ) : null,
    Yii::app()->user->checkAccess('usersReport') ? array(
        'label' => Yii::t('main-ui', 'Users report'),
        'icon' => 'file',
        'url' => array('/report/users')
    ) : null,
    Yii::app()->user->checkAccess('managersReport') ? array(
        'label' => Yii::t('main-ui', 'Managers report'),
        'icon' => 'file',
        'url' => array('/report/managers')
    ) : null,
    Yii::app()->user->checkAccess('serviceReport') ? array(
        'label' => Yii::t('main-ui', 'Service report'),
        'icon' => 'file',
        'url' => array('/report/servicenew')
    ) : null,
    Yii::app()->user->checkAccess('assetReport') ? array(
        'label' => Yii::t('main-ui', 'Assets report'),
        'icon' => 'file',
        'url' => array('/report/assets')
    ) : null,
    Yii::app()->user->checkAccess('unitProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Problems by unit'),
        'icon' => 'file',
        'url' => array('/report/unitproblem')
    ) : null,
    Yii::app()->user->checkAccess('monthServiceProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Service problems report by month'),
        'icon' => 'file',
        'url' => array('/report/problems')
    ) : null,
    Yii::app()->user->checkAccess('serviceProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Service problems report'),
        'icon' => 'file',
        'url' => array('/report/problems2')
    ) : null,
    Yii::app()->user->checkAccess('unitSProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Summary by Units'),
        'icon' => 'file',
        'url' => array('/report/unitgroups')
    ) : null,
    Yii::app()->user->checkAccess('requestSReport') ? array(
        'label' => Yii::t('main-ui', 'Summary by requests'),
        'icon' => 'file',
        'url' => array('/report/srequests')
    ) : null,
    Yii::app()->user->checkAccess('customReport') ? array(
        'label' => Yii::t('main-ui', 'Custom report'),
        'icon' => 'file',
        'url' => array('/report/customreport')
    ) : null,
));
$settings = array_filter(array(
    Yii::app()->user->checkAccess('rolesSettings') ? array(
        'label' => Yii::t('main-ui', 'Roles management'),
        'icon' => 'icon-group',
        'url' => array('/roles')
    ) : null,
    Yii::app()->user->checkAccess('mainSettings') ? array(
        'label' => Yii::t('main-ui', 'Main settings'),
        'icon' => 'icon-wrench',
        'url' => array('/config/main')
    ) : null,
    Yii::app()->user->checkAccess('mailParserSettings') ? array(
        'label' => Yii::t('main-ui', 'Mail parser'),
        'icon' => 'icon-envelope',
        'url' => array('/config/getmail')
    ) : null,
    Yii::app()->user->checkAccess('adSettings') ? array(
        'label' => Yii::t('main-ui', 'AD integration'),
        'icon' => 'icon-magic',
        'url' => array('/config/ad')
    ) : null,
    Yii::app()->user->checkAccess('smsSettings') ? array(
        'label' => Yii::t('main-ui', 'SMS gate'),
        'icon' => 'icon-comment',
        'url' => array('/config/sms')
    ) : null,
    Yii::app()->user->checkAccess('ticketSettings') ? array(
        'label' => Yii::t('main-ui', 'Ticket defaults'),
        'icon' => 'icon-edit',
        'url' => array('/config/request')
    ) : null,
    Yii::app()->user->checkAccess('attachSettings') ? array(
        'label' => Yii::t('main-ui', 'Attachments'),
        'icon' => 'icon-paper-clip',
        'url' => array('/config/attach')
    ) : null,
    Yii::app()->user->checkAccess('appearSettings') ? array(
        'label' => Yii::t('main-ui', 'Appearance'),
        'icon' => 'icon-adjust',
        'url' => array('/config/appear')
    ) : null,
    Yii::app()->user->checkAccess('shedulerSettings') ? array(
        'label' => Yii::t('main-ui', 'Manage cron jobs'),
        'icon' => 'icon-calendar',
        'url' => array('/cron/index')
    ) : null,
    Yii::app()->user->checkAccess('logSettings') ? array(
        'label' => Yii::t('main-ui', 'Log analyzer'),
        'icon' => 'icon-check',
        'url' => array('/log/index')
    ) : null,
    Yii::app()->user->checkAccess('logSettings') ? array(
        'label' => Yii::t('main-ui', 'Update'),
        'icon' => 'icon-check',
        'url' => array('/config/update')
    ) : null,
    Yii::app()->user->checkAccess('backupSettings') ? array(
        'label' => Yii::t('main-ui', 'Backups'),
        'icon' => 'icon-upload',
        'url' => array('/backup/default/index')
    ) : null,
    Yii::app()->user->checkAccess('importSettings') ? array(
        'label' => Yii::t('main-ui', 'Import from CSV'),
        'icon' => 'icon-download',
        'url' => array('/import/module/index')
    ) : null,
    (Yii::app()->user->checkAccess('adSettings') AND Yii::app()->ldap_conf->ad_enabled == 1) ? array(
        'label' => Yii::t('main-ui', 'Import CU from AD'),
        'icon' => 'icon-hdd',
        'url' => array('config/adcomputersimport')
    ) : null,
));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
          media="print"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
          media="screen, projection"/>
    <![endif]-->
    <link rel="icon" href="/images/icons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/images/icons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>
    <title><?php echo Yii::t('main-ui', 'Univef Service Desk system'); ?></title>
</head>

<body>
<div class="no_print">
    <?php
    if (!Yii::app()->user->isGuest) {
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'null',// null or 'inverse'
            'brand' => '<img src="' . Yii::app()->params->smallLogo . '" /> ' . Yii::app()->params->brandName,
            'brandUrl' => '/',
            'fixed' => 'top',
            'fluid' => 'true',
            'collapse' => true,// requires bootstrap - responsive.css
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array_filter(array(
                        count($create) > 0 ? array(
                            'label' => Yii::t('main-ui', 'Create'),
                            'icon' => 'fa-solid fa-plus',
                            'url' => '#',
                            'items' => $create,
                        ) : null,
                        count($references) > 0 ? array(
                            'label' => Yii::t('main-ui', 'References'),
                            'icon' => 'table',
                            'url' => '#',
                            'items' => $references,
                        ) : null,
                        count($reports) > 0 ? array(
                            'label' => Yii::t('main-ui', 'Reports'),
                            'icon' => 'file',
                            'url' => '#',
                            'items' => $reports,
                        ) : null,
                        count($settings) > 0 ? array(
                            'label' => Yii::t('main-ui', 'Settings'),
                            'icon' => 'cogs',
                            'url' => '#',
                            'items' => $settings,
                        ) : null,
                        '---',
                        array(
                            'label' => Yii::t('main-ui', 'Help'),
                            'icon' => 'question-sign',
                            'url' => '#',
                            'items' => array_filter(array(
                                array(
                                    'label' => Yii::t('main-ui', 'Instruction'),
                                    'icon' => 'icon-bookmark',
                                    'url' => array('/docs/instruction.pdf')
                                ),
                                Yii::app()->user->checkAccess('systemAdmin') ? array(
                                    'label' => Yii::t('main-ui', 'Licensing'),
                                    'icon' => 'icon-key',
                                    'url' => array('/config/lic')
                                ) : null,
                            ))
                        ),
                        $login,
                        $logout,

                    ))
                )
            )
        ));
    } else {
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'null',// null or 'inverse'
            'brand' => '<img src="' . Yii::app()->params->smallLogo . '" /> ' . Yii::app()->params->brandName,
            'brandUrl' => '/',
            'fixed' => 'top',
            'fluid' => 'true',
            'collapse' => true,// requires bootstrap - responsive.css
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array(
                        '---',
                        array(
                            'label' => Yii::t('main-ui', 'Login'),
                            'url' => array('/site/login'),
                            'visible' => Yii::app()->user->isGuest
                        ),
                        array(
                            'label' => Yii::t('main-ui', 'Logout') . ' (' . Yii::app()->user->name . ')',
                            'url' => array('/site/logout'),
                            'visible' => !Yii::app()->user->isGuest
                        ),

                    )
                )
            )
        ));
    }
    ?>

</div>
<div class="brake_nav">
    <br/>
    <br/>
    <br/>
</div>
<div class="container-fluid" id="page">
    <?php echo $content; ?>
    <div class="clear"></div>
    <div id="flash_msg"
         style="width:450px; right:5px; position:fixed; top:auto; bottom:0px; z-index:1000; display:block; overflow:hidden;">

    </div>
    <div id="footer">
        <button class="js-push-button">
            Получать уведомления
        </button>
        <!--
    ExecutionTime: <?= round(Yii::getLogger()->executionTime, 3); ?>;
    MemoryUsage: <?= round(Yii::getLogger()->memoryUsage / 1024 / 1024, 3) . " MB"; ?>;
    -->
    </div>
    <!-- footer -->

</div>
<!-- page -->
<audio>
    <source src="/images/alert.mp3">
</audio>

</body>
</html>


<?php if (!Yii::app()->user->checkAccess('systemAdmin') AND Yii::app()->params->use_rapid_msg == 1): ?>

    <script>
        var deletes = setInterval(function () {
            $('#flash_msg').load('/msg/deleteall');
        }, 600000);
        var audio = document.getElementsByTagName("audio")[0];
        function Messanger() {
            this.last = 0;
            this.timeout = 360;
            this.comet = 0;
            this.deletes = 0;
            var self = this;
            this.putMessage = function (id, name, user, text) {
                // callback, добавляет сообщения на страницу, вызывается из полученных с сервера данных
                self.last = id;
                var b = document.createElement('div');
                var token = "<?php echo Yii::app()->request->csrfToken; ?>";
                b.innerHTML = '<div class="alert alert-error"><button type="button" id="' + id + '" token="' + token + '" class="close" data-dismiss="alert">&times;</button> <b><a href="/request/viewsingle/?id=' + name + '&alert=' + id + '">[Ticket #' + name + ']</a></b> ' + text + '</div>';
                $('#flash_msg').append(b).fadeIn("slow");
                $.fn.yiiGridView.update('request-grid');
                audio.play();

            }
            this.parseData = function (message) {
                // простая обработка данных полученных с сервера, разбиваем строки и выполняет функции
                var items = message.split(';');
                if (items.length < 1) return false;
                for (var i = 0; i < items.length; i++) {
                    eval(items[i]);
                }
                setTimeout(self.connection, 1000);
            }
            this.connection = function () {
                // здесь открывается соединение с сервером
                self.comet = $.ajax({
                    type: "GET",
                    url: "/msg/comet",
                    data: {'id': self.last},
                    dataType: "text",
                    timeout: self.timeout * 1000,
                    success: self.parseData,
                    error: function () {
                        // something wrong. but setInterval will set up connection automatically
                        setTimeout(self.connection, 1000);
                    }
                });
            }

            this.init = function () {
                //setInterval(self.connection,self.timeout*1000);
                self.connection();
            }
            this.init();
        }

        $(document).ready(function () {
            // инициализация
            var msg = new Messanger();
        });
        $(document).ready(function () {
            // инициализация
            $(".close").live('click', function () {
                var id = $(this).attr("id");
                var csrf = $(this).attr("token");
                $.ajax({
                    type: "POST",
                    url: "/msg/delete",
                    data: {'id': id, 'YII_CSRF_TOKEN': csrf},
                    dataType: "text",
                    cache: false,
                    error: function (e) {
                        console.log(e);
                    }
                });
                return false;

            });
        });

    </script>
<?php endif; ?>
