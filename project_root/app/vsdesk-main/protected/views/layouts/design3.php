<?php

if (Yii::app()->user->checkAccess('readChat')) {
    Yii::app()->clientScript->registerScriptFile('/js/socket.privates.js', CClientScript::POS_END);
}
if (!isset($_COOKIE['NAVCOLLAPSE'])) {
    setcookie("NAVCOLLAPSE", "2", time() + 2400000, "/");
}
$cookie = $_COOKIE;
$rcreate = array(
    'label' => Yii::t('main-ui', 'Create ticket'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => '/request/create',
    false
);
$pcreate = array(
    'label' => Yii::t('main-ui', 'Problem by incident'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/problems/create')
);
$pcreateh = array(
    'label' => Yii::t('main-ui', 'Problem by asset'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/problems/createh')
);
$acreate = array('label' => Yii::t('main-ui', 'Create asset'), 'icon' => 'fa-solid fa-hard-drive', 'url' => array('/asset/create'));
$aacreate = array(
    'label' => Yii::t('main-ui', 'Create new asset type'),
    'icon' => 'fa-solid fa-hard-drive',
    'url' => array('/assetAttrib/create')
);
$ucreate = array('label' => Yii::t('main-ui', 'Create unit'), 'icon' => 'fa-solid fa-hard-drive', 'url' => array('/cunits/create'));
$utcreate = array(
    'label' => Yii::t('main-ui', 'Create new unit type'),
    'icon' => 'fa-solid fa-hard-drive',
    'url' => array('/cunitTypes/create')
);
$screate = array(
    'label' => Yii::t('main-ui', 'Create service'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/service/create')
);
$slacreate = array(
    'label' => Yii::t('main-ui', 'Create service level'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/sla/create')
);
$kcreate = array(
    'label' => Yii::t('main-ui', 'Create new record'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/knowledge/module/create')
);
$kccreate = array(
    'label' => Yii::t('main-ui', 'Create new category'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/knowledge/category/create')
);
$ncreate = array(
    'label' => Yii::t('main-ui', 'Create news'),
    'icon' => 'fa-solid fa-circle-plus',
    'url' => array('/news/module/create')
);
// $ckcreate = array(
//     'label' => Yii::t('main-ui', 'Create contract'),
//     'icon' => 'fa-solid fa-circle-plus',
//     'url' => array('/contracts/create')
// );
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
    'label' => null,
    'icon' => 'fa-solid fa-right-from-bracket fa-xl',
    'url' => array('/site/logout'),
    'visible' => !Yii::app()->user->isGuest
);
$create = array_filter(array(
    Yii::app()->user->checkAccess('createRequest') ? array('label' => Yii::t('main-ui', 'Tickets')) : null,
    Yii::app()->user->checkAccess('createRequest') ? $rcreate : null,
    Yii::app()->user->checkAccess('createProblem') ? array('label' => Yii::t('main-ui', 'Problems')) : null,
    Yii::app()->user->checkAccess('createProblem') ? $pcreate : null,
    Yii::app()->user->checkAccess('createProblem') ? $pcreateh : null,
    (Yii::app()->user->checkAccess('createAsset') or Yii::app()->user->checkAccess('createAssetType') or Yii::app()->user->checkAccess('createUnit') or Yii::app()->user->checkAccess('createUnitType')) ? array(
        'label' => Yii::t('main-ui', 'Assets')
    ) : null,
    Yii::app()->user->checkAccess('createAsset') ? $acreate : null,
    Yii::app()->user->checkAccess('createAssetType') ? $aacreate : null,
    Yii::app()->user->checkAccess('createUnit') ? $ucreate : null,
    Yii::app()->user->checkAccess('createUnitType') ? $utcreate : null,
    (Yii::app()->user->checkAccess('createService') or Yii::app()->user->checkAccess('createSla')) ? array(
        'label' => Yii::t('main-ui', 'Services')
    ) : null,
    Yii::app()->user->checkAccess('createService') ? $screate : null,
    Yii::app()->user->checkAccess('createSla') ? $slacreate : null,
    (Yii::app()->user->checkAccess('createKB') or Yii::app()->user->checkAccess('createKBCat')) ? array(
        'label' => Yii::t('main-ui', 'Knowledgebase')
    ) : null,
    Yii::app()->user->checkAccess('createKB') ? $kcreate : null,
    Yii::app()->user->checkAccess('createKBCat') ? $kccreate : null,
    Yii::app()->user->checkAccess('createNews') ? $ncreate : null,
    // (Yii::app()->user->checkAccess('createContracts')) ? array(
    //     'label' => Yii::t('main-ui', 'Contracts')
    // ) : null,
    Yii::app()->user->checkAccess('createContracts') ? $ckcreate : null,
));
$references = array_filter(array(
    Yii::app()->user->checkAccess('listUser') ? array(
        'label' => Yii::t('main-ui', 'Users'),
        'icon' => 'fa-solid fa-user',
        'url' => array('/cusers/index')
    ) : null,
    Yii::app()->user->checkAccess('listCompany') ? array(
        'label' => Yii::t('main-ui', 'Companies'),
        'icon' => 'fa-solid fa-building',
        'url' => array('/companies/index')
    ) : null,
    Yii::app()->user->checkAccess('listCompany') ? array(
        'label' => Yii::t('main-ui', 'Cities'),
        'icon' => 'fa-solid fa-building',
        'url' => array('/cities/index')
    ) : null,
    Yii::app()->user->checkAccess('listCompany') ? array(
        'label' => Yii::t('main-ui', 'Streets'),
        'icon' => 'fa-solid fa-building',
        'url' => array('/streets/index')
    ) : null,
    Yii::app()->user->checkAccess('listDepart') ? array(
        'label' => Yii::t('main-ui', 'Departments'),
        'icon' => 'fa-solid fa-users',
        'url' => array('/depart/index')
    ) : null,
    Yii::app()->user->checkAccess('listGroup') ? array(
        'label' => Yii::t('main-ui', 'Groups'),
        'icon' => 'fa-solid fa-user-group',
        'url' => array('/groups/index')
    ) : null,
    Yii::app()->user->checkAccess('listServiceCategory') ? [
        'label' => Yii::t('main-ui', 'Service categories'),
        'icon' => 'fa-solid fa-layer-group',
        'url' => ['/servicecategories/index']
    ] : null,
    Yii::app()->user->checkAccess('listTcategory') ? [
        'label' => Yii::t('main-ui', 'Ticket tcategories'),
        'icon' => 'fa-solid fa-layer-group',
        'url' => ['/tcategory/index']
    ] : null,
    // Yii::app()->user->checkAccess('listService') ? array(
    //     'label' => Yii::t('main-ui', 'Services'),
    //     'icon' => 'fa-solid fa-layer-group',
    //     'url' => array('/service/index')
    // ) : null,
    // Yii::app()->user->checkAccess('listSla') ? array(
    //     'label' => Yii::t('main-ui', 'Service level'),
    //     'icon' => 'fa-solid fa-chart-line',
    //     'url' => array('/sla/index')
    // ) : null,
    // Yii::app()->user->checkAccess('listContracts') ? array(
    //     'label' => Yii::t('main-ui', 'Contracts'),
    //     'icon' => 'fa-solid fa-file',
    //     'url' => array('/contracts/index')
    // ) : null,
    Yii::app()->user->checkAccess('listPriority') ? array(
        'label' => Yii::t('main-ui', 'Ticket Priority'),
        'icon' => 'fa-solid fa-clock',
        'url' => array('/zpriority/index')
    ) : null,
    Yii::app()->user->checkAccess('listAssetType') ? array(
        'label' => Yii::t('main-ui', 'Asset types'),
        'icon' => 'fa-solid fa-desktop',
        'url' => array('/assetAttrib/index')
    ) : null,
    Yii::app()->user->checkAccess('listUnitType') ? array(
        'label' => Yii::t('main-ui', 'CU types'),
        'icon' => 'fa-solid fa-computer',
        'url' => array('/cunitTypes/index')
    ) : null,
    // Yii::app()->user->checkAccess('listKB') ? array(
    //     'label' => Yii::t('main-ui', 'Knowledgebase'),
    //     'icon' => 'fa-solid fa-book',
    //     'url' => array('/knowledge/module/index')
    // ) : null,
    Yii::app()->user->checkAccess('listKBCat') ? array(
        'label' => Yii::t('main-ui', 'Knowledgebase cats'),
        'icon' => 'fa-solid fa-book',
        'url' => array('/knowledge/category/index')
    ) : null,
    Yii::app()->user->checkAccess('listNews') ? array(
        'label' => Yii::t('main-ui', 'News'),
        'icon' => 'fa-solid fa-newspaper',
        'url' => array('/news/module/index')
    ) : null,
    Yii::app()->user->checkAccess('listStatus') ? array(
        'label' => Yii::t('main-ui', 'Statuses'),
        'icon' => 'fa-solid fa-tag',
        'url' => array('/status/index')
    ) : null,
    Yii::app()->user->checkAccess('listAstatus') ? array(
        'label' => Yii::t('main-ui', 'Unit statuses'),
        'icon' => 'fa-solid fa-tag',
        'url' => array('/astatus/index')
    ) : null,
    Yii::app()->user->checkAccess('listCategory') ? array(
        'label' => Yii::t('main-ui', 'Request categories'),
        'icon' => 'fa-solid fa-inbox',
        'url' => array('/category/index')
    ) : null,
    Yii::app()->user->checkAccess('listETemplate') ? array(
        'label' => Yii::t('main-ui', 'E-mail templates'),
        'icon' => 'fa-solid fa-pen-to-square',
        'url' => array('/messages/index')
    ) : null,
    Yii::app()->user->checkAccess('listSTemplate') ? array(
        'label' => Yii::t('main-ui', 'SMS templates'),
        'icon' => 'fa-solid fa-pen-to-square',
        'url' => array('/smss/index')
    ) : null,
    Yii::app()->user->checkAccess('listTemplates') ? array(
        'label' => Yii::t('main-ui', 'Reply templates'),
        'icon' => 'fa-solid fa-pen-to-square',
        'url' => array('/replytemplates/index')
    ) : null,
    Yii::app()->user->checkAccess('listUnitTemplates') ? array(
        'label' => Yii::t('main-ui', 'Print form templates'),
        'icon' => 'fa-solid fa-pen-to-square',
        'url' => array('/unittemplates/index')
    ) : null,
    Yii::app()->user->checkAccess('listChecklists') ? array(
        'label' => Yii::t('main-ui', 'Checklists'),
        'icon' => 'fa-solid fa-list-check',
        'url' => array('/checklists/index')
    ) : null,
    Yii::app()->user->checkAccess('listFieldsets') ? array(
        'label' => Yii::t('main-ui', 'Fieldsets'),
        'icon' => 'fa-solid fa-list-check',
        'url' => array('/fieldsets/index')
    ) : null,
    Yii::app()->user->checkAccess('listSelects') ? array(
        'label' => Yii::t('main-ui', 'Lists'),
        'icon' => 'fa-solid fa-square-caret-down',
        'url' => array('/selects/index')
    ) : null,
));
$reports = array_filter(array(
    Yii::app()->user->checkAccess('companiesReport') ? array(
        'label' => Yii::t('main-ui', 'Companies report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/companies')
    ) : null,
    Yii::app()->user->checkAccess('usersReport') ? array(
        'label' => Yii::t('main-ui', 'Users report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/users')
    ) : null,
    Yii::app()->user->checkAccess('managersReport') ? array(
        'label' => Yii::t('main-ui', 'Managers report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/managers')
    ) : null,
    Yii::app()->user->checkAccess('managersKPIReport') ? array(
        'label' => Yii::t('main-ui', 'KPI report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/kpi')
    ) : null,
    Yii::app()->user->checkAccess('serviceReport') ? array(
        'label' => Yii::t('main-ui', 'Service report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/servicenew')
    ) : null,
    Yii::app()->user->checkAccess('assetReport') ? array(
        'label' => Yii::t('main-ui', 'Assets report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/assets')
    ) : null,
    Yii::app()->user->checkAccess('unitProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Problems by unit'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/unitproblem')
    ) : null,
    Yii::app()->user->checkAccess('monthServiceProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Service problems report by month'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/problems')
    ) : null,
    Yii::app()->user->checkAccess('serviceProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Service problems report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/problems2')
    ) : null,
    Yii::app()->user->checkAccess('unitSProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Summary by Units'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/unitgroups')
    ) : null,
    Yii::app()->user->checkAccess('unitSProblemReport') ? array(
        'label' => Yii::t('main-ui', 'Summary by Actives'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/actives')
    ) : null,
    Yii::app()->user->checkAccess('requestSReport') ? array(
        'label' => Yii::t('main-ui', 'Summary by requests'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/srequests')
    ) : null,
    Yii::app()->user->checkAccess('customReport') ? array(
        'label' => Yii::t('main-ui', 'Custom report'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/customreport')
    ) : null,
    Yii::app()->user->checkAccess('serviceReport') ? array(
        'label' => Yii::t('main-ui', 'Fields by service'),
        'icon' => 'fa-regular fa-file',
        'url' => array('/report/allfields')
    ) : null,
));
$settings = array_filter(array(
    Yii::app()->user->checkAccess('mainSettings') ? array(
        'label' => Yii::t('main-ui', 'Main settings'),
        'icon' => 'fa-solid fa-hammer',
        'url' => array('/config/main')
    ) : null,
    (Yii::app()->user->checkAccess('rolesSettings')) ? array(
        'label' => Yii::t('main-ui', 'Roles management'),
        'icon' => 'fa-solid fa-user-lock',
        'url' => array('/roles')
    ) : null,
    Yii::app()->user->checkAccess('mailParserSettings') ? array(
        'label' => Yii::t('main-ui', 'Email configurations'),
        'icon' => 'fa-solid fa-envelope',
        'url' => array('/config/getmail')
    ) : null,
    Yii::app()->user->checkAccess('listRequestProcessingRules') ? array( // TODO: !!!
        'label' => Yii::t('main-ui', 'Request processing rules'),
        'icon' => 'fa-solid fa-sitemap',
        'url' => array('/requestprocessingrules')
    ) : null,
    Yii::app()->user->checkAccess('pushSettings') ? array(
        'label' => Yii::t('main-ui', 'Push notification'),
        'icon' => 'fa-regular fa-comment-dots',
        'url' => array('/config/push')
    ) : null,
    (Yii::app()->user->checkAccess('tbotSettings')) ? array(
        'label' => Yii::t('main-ui', 'Telegram bot integration'),
        'icon' => 'fa-brands fa-telegram',
        'url' => array('/config/tbot')
    ) : null,
    // (Yii::app()->user->checkAccess('vbotSettings')) ? array(
    //     'label' => Yii::t('main-ui', 'Viber bot integration'),
    //     'icon' => 'fa-brands fa-viber',
    //     'url' => array('/config/vbot')
    // ) : null,
    // (Yii::app()->user->checkAccess('msbotSettings')) ? array(
    //     'label' => Yii::t('main-ui', 'Microsoft Bot integration'),
    //     'icon' => 'fa-solid fa-face-smile',
    //     'url' => array('/config/msbot')
    // ) : null,
    (Yii::app()->user->checkAccess('wbotSettings')) ? array(
        'label' => Yii::t('main-ui', 'WhatsApp integration'),
        'icon' => 'fa-brands fa-whatsapp',
        'url' => array('/config/whatsapp')
    ) : null,
    // (Yii::app()->user->checkAccess('slackSettings')) ? array(
    //     'label' => Yii::t('main-ui', 'Slack integration'),
    //     'icon' => 'fa-brands fa-slack',
    //     'url' => array('/config/slack')
    // ) : null,
    (Yii::app()->user->checkAccess('twSettings')) ? array(
        'label' => Yii::t('main-ui', 'TeamViewer integration'),
        'icon' => 'fa-solid fa-network-wired',
        'url' => array('/config/tw')
    ) : null,
    (Yii::app()->user->checkAccess('widgetSettings')) ? array(
        'label' => Yii::t('main-ui', 'Site widget settings'),
        'icon' => 'fa-solid fa-code',
        'url' => array('/config/widget')
    ) : null,
    (Yii::app()->user->checkAccess('portalSettings')) ? array(
        'label' => Yii::t('main-ui', 'Portal settings'),
        'icon' => 'fa-solid fa-sliders	',
        'url' => array('/config/portal')
    ) : null,
    // (Yii::app()->user->checkAccess('jiraSettings')) ? [
    //     'label' => Yii::t('main-ui', 'Jira integration'),
    //     'icon' => 'fa-brands fa-jira fa-xl',
    //     'url' => ['/config/jira']
    // ] : null,
    (Yii::app()->user->checkAccess('adSettings')) ? array(
        'label' => Yii::t('main-ui', 'LDAP integration'),
        'icon' => 'fa-solid fa-folder-tree',
        'url' => array('/config/ad')
    ) : null,
    // (Yii::app()->user->checkAccess('amiSettings')) ? array(
    //     'label' => Yii::t('main-ui', 'Asterisk integration'),
    //     'icon' => 'fa-solid fa-asterisk',
    //     'url' => array('/config/ami')
    // ) : null,
    (Yii::app()->user->checkAccess('smsSettings')) ? array(
        'label' => Yii::t('main-ui', 'SMS gate'),
        'icon' => 'fa-solid fa-comment-sms',
        'url' => array('/config/sms')
    ) : null,
    Yii::app()->user->checkAccess('ticketSettings') ? array(
        'label' => Yii::t('main-ui', 'Ticket defaults'),
        'icon' => 'fa-solid fa-ticket',
        'url' => array('/config/request')
    ) : null,
    Yii::app()->user->checkAccess('attachSettings') ? array(
        'label' => Yii::t('main-ui', 'Attachments'),
        'icon' => 'fa-solid fa-paperclip',
        'url' => array('/config/attach')
    ) : null,
    Yii::app()->user->checkAccess('appearSettings') ? array(
        'label' => Yii::t('main-ui', 'Appearance'),
        'icon' => 'fa-solid fa-shirt',
        'url' => array('/config/appear')
    ) : null,
    Yii::app()->user->checkAccess('shedulerSettings') ? array(
        'label' => Yii::t('main-ui', 'Manage cron jobs'),
        'icon' => 'fa-solid fa-rotate',
        'url' => array('/cron/index')
    ) : null,
    (Yii::app()->user->checkAccess('adminChat')) ? array(
        'label' => Yii::t('main-ui', 'Chat admin'),
        'icon' => 'fa-solid fa-message',
        'url' => array('/chat/admin')
    ) : null,
    Yii::app()->user->checkAccess('logSettings') ? array(
        'label' => Yii::t('main-ui', 'Log analyzer'),
        'icon' => 'fa-solid fa-list-check',
        'url' => array('/log/index')
    ) : null,
    // Yii::app()->user->checkAccess('systemAdmin') ? array(
    //     'label' => Yii::t('main-ui', 'Update'),
    //     'icon' => 'fa-solid fa-square-up-right',
    //     'url' => array('/config/update')
    // ) : null,
    Yii::app()->user->checkAccess('backupSettings') ? array(
        'label' => Yii::t('main-ui', 'Backups'),
        'icon' => 'fa-solid fa-box-archive',
        'url' => array('/backup/default/index')
    ) : null,
    Yii::app()->user->checkAccess('importSettings') ? array(
        'label' => Yii::t('main-ui', 'Import from CSV'),
        'icon' => 'fa-solid fa-file-csv',
        'url' => array('/import/module/index')
    ) : null,
));
$this->menu = array_filter(array(
    count($create) > 0 ? array(
        'label' => null,
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => '#',
        'items' => $create,
    ) : null
));
$newMessages = null;
$settings_menu = array_filter(array(
    ((!Yii::app()->user->isGuest and isset($_SERVER['HTTPS'])) or (!Yii::app()->user->isGuest and $_SERVER['HTTP_HOST'] == 'localhost')) ? array(
        'label' => null,
        'icon' => 'fa-solid fa-bell fa-xl',
        'url' => '',
        'itemOptions' => array('class' => 'js-push-button')
    ) : null,
    (Yii::app()->user->checkAccess('readChat')) ? array(
        'label' => '<span id="msg_count" class="label label-success">' . $newMessages . '</span>',
        'icon' => 'fa-solid fa-comments fa-xl',
        'url' => '/chat/privates?user=main',
        'itemOptions' => array('class' => 'dropdown messages-menu')
    ) : null,
    count($settings) > 0 ? array(
        'label' => null,
        'icon' => 'fa-solid fa-gear fa-xl',
        'url' => '#',
        'items' => $settings,
    ) : null,
    array(
        'label' => null,
        'icon' => 'fa-regular fa-circle-question fa-xl',
        'url' => '#',
        'items' => array_filter(array(
            Yii::app()->user->checkAccess('systemAdmin') ? array(
                'label' => Yii::t('main-ui', 'System environment'),
                'icon' => 'fa-solid fa-code',
                'url' => array('/site/environment'),
                'linkOptions' => array(
                    'target' => '_blank',
                ),
            ) : null,
            // Yii::app()->user->checkAccess('systemAdmin') ? array(
            //     'label' => Yii::t('main-ui', 'Licensing'),
            //     'icon' => 'fa-solid fa-file-contract',
            //     'url' => array('/config/lic')
            // ) : null,
        ))
    ),
    $login,
    $logout,

));
$fullname = CUsers::model()->findByPk(Yii::app()->user->id);
?>
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
		<link rel="apple-touch-icon" href="<?php
        echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="57x57"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-57x57.png"/>
		<link rel="apple-touch-icon" sizes="72x72"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-72x72.png"/>
		<link rel="apple-touch-icon" sizes="76x76"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-76x76.png"/>
		<link rel="apple-touch-icon" sizes="114x114"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-114x114.png"/>
		<link rel="apple-touch-icon" sizes="120x120"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-120x120.png"/>
		<link rel="apple-touch-icon" sizes="144x144"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-144x144.png"/>
		<link rel="apple-touch-icon" sizes="152x152"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-152x152.png"/>
		<link rel="apple-touch-icon" sizes="180x180"
			  href="<?php
              echo Yii::app()->request->baseUrl; ?>/images/icons/apple-touch-icon-180x180.png"/>
		<script src="https://kit.fontawesome.com/1c8c98423f.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="<?php
        echo Yii::app()->request->baseUrl; ?>/css/form.css"/>
		<title><?php
            if (!empty(Yii::app()->params['pageHeader'])) {
                echo Yii::app()->params['pageHeader'];
            } else {
                echo Yii::t('main-ui', 'Univef Service Desk system');
            } ?></title>
        <?php
        if (isset($_SERVER['HTTPS']) or $_SERVER['HTTP_HOST'] == 'localhost'): ?>
        <?php
        endif; ?>
		<!-- Theme style -->
		<link rel="stylesheet" href="/css/AdminLTE.css">
		<!-- Sweet alert -->
		<link rel="stylesheet" href="/css/sweetalert2.min.css">
		<script src="/js/sweetalert2.min.js"></script>
		<!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="/css/skins/_all-skins.min.css">
		<link rel="stylesheet" href="/css/skins/uvf/main.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="/js/html5shiv.min.js"></script>
		<script src="/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition <?php
    if ($cookie['NAVCOLLAPSE'] === "1"): ?>sidebar-collapse<?php
    endif; ?> <?php
    if (isset(Yii::app()->params->fixedPanel) and Yii::app()->params->fixedPanel == "1") {
        echo 'fixed';
    } ?>  sidebar-mini">
		<div class="wrapper">

			<header class="main-header">
				<!-- Logo -->
				<a href="/" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini">
				<img src="/images/logos/logo-small-white.svg" alt="logo">
			</span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg">
				<img src="/images/logos/logo-full-white.svg" alt="logo">
			</span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<i class="fa-solid fa-bars"></i>
						<span class="sr-only"></span>
					</a>
                    <?php
                    $this->widget('bootstrap.widgets.TbMenu', array(
                        'type' => 'pills',
                        'items' => $this->menu,
                    )); ?>

                    <?php
                    if (!Yii::app()->user->isGuest): ?>
						<form class="navbar-form pull-left" autocomplete="off" action="/search">
							<div class="input-append">
								<input class="span2" id="appendedInputButtons" type="text" name="q" placeholder="Ключевые слова">
								<button class="btn btn-small" type="submit">
									<i class="fa-solid fa-magnifying-glass"></i>
								</button>
							</div>
						</form>
                    <?php
                    endif; ?>

					<div class="navbar-custom-menu">
                        <?php
                        $this->widget('bootstrap.widgets.TbMenu', array(
                            'encodeLabel' => false,
                            'type' => 'pills',
                            'htmlOptions' => array('class' => 'pull-right'),
                            'items' => $settings_menu,
                        )); ?>
					</div>
				</nav>

			</header>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<div class="slimScrollDiv">
					<!-- sidebar: style can be found in sidebar.less -->
					<section class="sidebar">
						<!-- Sidebar user panel -->
						<div class="user-panel">
							<div class="pull-left image">
								<a href="/cusers/<?php
                                echo Yii::app()->user->id; ?>">
                                    <?php
                                    if ($fullname->photo): ?>
										<img src="/media/userphoto/<?= $fullname->id ?>.png" width="35" class="img-circle" alt="<?= $fullname->fullname ?>">
                                    <?php
                                    else: ?>
										<img src="/images/icons/circle-user-solid.svg" height="35" width="35" class="img-circle" alt="User Image">
                                    <?php
                                    endif; ?>
								</a>
							</div>
							<div class="pull-left info">
                                <?php
                                if (mb_strlen($fullname->fullname) > 22) {
                                    $flname = mb_substr($fullname->fullname, 0, 20, 'UTF-8') . '..';
                                } else {
                                    $flname = $fullname->fullname;
                                } ?>
								<p><?php
                                    echo $flname; ?></p>
								<a href="#"><i class="icon-circle text-success"></i> <?php
                                    echo $fullname->role_name; ?></a>
							</div>
						</div>
						<!-- sidebar menu: : style can be found in sidebar.less -->
						<ul class="sidebar-menu">
							<li class="header"><?php
                                echo Yii::t('main-ui', 'Main menu'); ?></li>
							<li class="<?php
                            if ($_SERVER['REQUEST_URI'] === '/' or $_SERVER['REQUEST_URI'] === '/portal') : ?>active<?php
                            endif; ?> treeview">
								<a href="/">
									<i class="fa-solid fa-house"></i>
									<span>&nbsp;<?php
                                        echo Yii::t('main-ui', 'Dashboard'); ?></span>
								</a>
							</li>
                            <?php
                            if (Yii::app()->user->checkAccess('listRequest')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/request/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/request/">
										<i class="fa-solid fa-ticket"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Tickets'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>

                            <?php
                            if (Yii::app()->user->checkAccess('listCronRequest')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/cronreq/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/cronreq/">
										<i class="fa-solid fa-calendar-days"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Cron Requests'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>

                            <?php
                            if (Yii::app()->user->checkAccess('listProblem')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/problems/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/problems/">
										<i class="fa-solid fa-triangle-exclamation"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Problems'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::app()->user->checkAccess('listService')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/service/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/service/">
										<i class="fa-solid fa-layer-group"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Services'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::app()->user->checkAccess('listSla')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/sla/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/sla/">
										<i class="fa-solid fa-chart-line"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'SLA'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::app()->user->checkAccess('listAsset')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/asset/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/asset/">
										<i class="fa-solid fa-desktop"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Assets'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::app()->user->checkAccess('listUnit')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/cunits/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/cunits/">
										<i class="fa-solid fa-computer"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Units'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::app()->user->checkAccess('listKB') or Yii::app()->user->isGuest): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/knowledge/module/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/knowledge/module/">
										<i class="fa-solid fa-book"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Knowledgebase'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <!-- <?php
                            if (Yii::app()->user->checkAccess('listCalls')): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/calls/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/calls/">
										<i class="fa-solid fa-phone"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Calls'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?> -->
                            <?php
                            if (Yii::app()->user->checkAccess('listPhonebook') or (Yii::app()->user->isGuest and Yii::app()->params['portalPhonebook'] == 1)): ?>
								<li class="<?php
                                if ($_SERVER['REQUEST_URI'] === '/phonebook/') : ?>active<?php
                                endif; ?> treeview">
									<a href="/phonebook/">
										<i class="fa-solid fa-address-book"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Phonebook'); ?></span>
									</a>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (count($references) > 0): ?>
								<li class="treeview">
									<a href="#">
										<i class="fa-solid fa-database"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'References'); ?></span>
										<i class="fa-solid fa-chevron-left fa-xs pull-right"></i>
									</a>
                                    <?php
                                    $this->widget('bootstrap.widgets.TbMenu', array(
                                        'htmlOptions' => array('class' => 'treeview-menu'),
                                        'items' => $references,
                                    )); ?>
								</li>
                            <?php
                            endif; ?>
                            <?php
                            if (count($reports) > 0): ?>
								<li class="treeview">
									<a href="#">
										<i class="fa-solid fa-chart-pie"></i>
										<span>&nbsp;<?php
                                            echo Yii::t('main-ui', 'Reports'); ?></span>
										<i<i class="fa-solid fa-chevron-left fa-xs pull-right"></i>
									</a>
                                    <?php
                                    $this->widget('bootstrap.widgets.TbMenu', array(
                                        'htmlOptions' => array('class' => 'treeview-menu'),
                                        'items' => $reports,
                                    )); ?>
								</li>
                            <?php
                            endif; ?>
						</ul>
					</section>
					<!-- /.sidebar -->
				</div>
			</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
                <?php
                $this->widget('bootstrap.widgets.TbAlert', array(
                    'block' => true,
                    'fade' => true,
                    'closeText' => '×',
                )); ?>
				<!-- Content Header (Page header) -->
				<section class="content-header">
                    <?php
                    if (isset($this->breadcrumbs)):?>
                        <?php
                        $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                            'links' => $this->breadcrumbs,
                            'separator' => '',
                        )); ?>
                    <?php
                    endif ?>

				</section>

				<!-- Main content -->
				<section class="content">
                    <?php
                    echo $content; ?>
				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->
			<div id="flash_msg"
				 style="width:450px; right:5px; position:fixed; top:auto; bottom:0px; z-index:1000; display:block; overflow:hidden;">

			</div>
            <?php
            if (isset(Yii::app()->params['showBtn']) and Yii::app()->params['showBtn'] == "1"): ?>
                <?php
                if ($_SERVER['REQUEST_URI'] == '/request/' or $_SERVER['REQUEST_URI'] == '/request/index' or $_SERVER['REQUEST_URI'] == '/request/kanban/' or $_SERVER['REQUEST_URI'] == '/request/kanban/index' or $_SERVER['REQUEST_URI'] == '/report/customreport' or $_SERVER['REQUEST_URI'] == '/report/customreport/index' or $_SERVER['REQUEST_URI'] == '/report/allFieldsReport' or $_SERVER['REQUEST_URI'] == '/report/allFieldsReport/index'): ?>
					<a href="javascript:void(0)" class="carousel-button-right" style="width: 64px; height: 64px; opacity: 0.3;
        position: fixed;
        bottom: 32px;
        right: 32px;
        background: url(/images/right.png) no-repeat left top;
        text-indent: -9999px;
        z-index: 999"></a>

					<a href="javascript:void(0)" class="carousel-button-left" style="width: 64px; height: 64px; opacity: 0.3;
        position: fixed;
        bottom: 32px;
        right: 100px;
        background: url(/images/left.png) no-repeat left top;
        text-indent: -9999px;
        z-index: 999"></a>
                <?php
                endif; ?>
            <?php
            endif; ?>
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
                    <?php
                    if (YII_DEBUG == true) {
                        echo '<span style="color: red;"> DEBUG </span>';
                        echo ' ExecutionTime: ' . round(Yii::getLogger()->executionTime, 3);
                        echo ' MemoryUsage: ' . round(Yii::getLogger()->memoryUsage / 1024 / 1024, 3) . " MB";
                    } ?> <b><?php
                        echo Yii::t('main-ui', 'Software version '); ?></b> <a href="/changelog.txt"
																			   target="_blank"> <?php
                        echo constant('version'); ?></a>
				</div>
			</footer>


			<!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
		</div><!-- ./wrapper -->

		<!-- AdminLTE App -->
		<script src="/js/app.min.js"></script>
		<!-- slimScroll -->
        <?php
        if (isset(Yii::app()->params->fixedPanel) and Yii::app()->params->fixedPanel == "1"): ?>
			<script src="/js/slimScroll/jquery.slimscroll.min.js"></script>
        <?php
        endif; ?>

		<audio>
			<source src="/images/alert.mp3">
		</audio>
        <?php
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $configPush = PushAPI::getScriptConfig();

        if ((!Yii::app()->user->isGuest and isset($_SERVER['HTTPS']) and !$iPhone and !$iPad and $configPush)
            or (!Yii::app()->user->isGuest and $_SERVER['HTTP_HOST'] == 'localhost') and !$iPhone and !$iPad and $configPush): ?>
			<script src="https://www.gstatic.com/firebasejs/4.6.2/firebase.js"></script>
			<script>
				$(function () {
					var issetToken = <?php echo PushAPI::getToken(Yii::app()->user->id) ? 1 : 0; ?>;

                    <?php echo $configPush ?>

					firebase.initializeApp(config);

					const messaging = firebase.messaging();

					if (Notification.permission === 'granted') {
						messaging.onMessage(function (payload) {
							console.log('Message received. ', payload);
							new Notification(payload.notification.title, payload.notification);
						});
					}

					if (Notification.permission === 'granted' && issetToken) {
						$('.js-push-button i').removeClass('fa-solid fa-bell-slash fa-xl');
						$('.js-push-button i').addClass('fa-solid fa-bell fa-xl');
					} else {
						$('.js-push-button i').removeClass('fa-solid fa-bell fa-xl');
						$('.js-push-button i').addClass('fa-solid fa-bell-slash fa-xl');
					}

					$('.js-push-button').on('click', function () {
						if (issetToken) {
							unsubscribe();
						} else {
							subscribe();
						}
					});

					function subscribe() {
						messaging.requestPermission()
							.then(function () {
								messaging.getToken()
									.then(function (currentToken) {
										console.log(currentToken);

										if (currentToken) {
											sendTokenToServer(currentToken);
										} else {
											console.warn('Не удалось получить токен.');
											setTokenSentToServer(false);
										}
									})
									.catch(function (err) {
										console.warn('При получении токена произошла ошибка.', err);
										setTokenSentToServer(false);
									});
							})
							.catch(function (err) {
								console.warn('Не удалось получить разрешение на показ уведомлений.', err);
							});
					}

					function unsubscribe() {
						messaging.getToken()
							.then(function (currentToken) {
								messaging.deleteToken(currentToken)
									.then(function () {
										console.log('Token deleted');
										delTokenFromServer(currentToken);
										setTokenSentToServer(false);
									})
									.catch(function (error) {
										console.log('Unable to delete token ' + error);
									});
							})
							.catch(function (error) {
								console.log('Error retrieving Instance ID token ' + error);
							});
					}

					function sendTokenToServer(currentToken) {
						if (!isTokenSentToServer(currentToken)) {
							let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
							console.log('Отправка токена на сервер...');
							let url = '/addsubscriber';
							$.post(url, {
								action:         'add',
								YII_CSRF_TOKEN: csrf,
								token:          currentToken
							});

							setTokenSentToServer(currentToken);
						} else {
							console.log('Токен уже отправлен на сервер.');
						}
					}

					function delTokenFromServer(currentToken) {
						if (currentToken) {
							let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
							console.log('Удаление токена на сервере...');
							let url = '/delsubscriber';
							$.post(url, {
								action:         'del',
								YII_CSRF_TOKEN: csrf,
								token:          currentToken
							});

							setTokenSentToServer(currentToken);
						}
					}

					function isTokenSentToServer(currentToken) {
						let userToken = "<?php echo PushAPI::getToken(Yii::app()->user->id) ?>";
						return userToken === currentToken;
					}

					function setTokenSentToServer(currentToken) {
						if (currentToken) {
							$('.js-push-button i').removeClass('fa-solid fa-bell-slash fa-xl');
							$('.js-push-button i').addClass('fa-solid fa-bell fa-xl');
						} else {
							$('.js-push-button i').removeClass('fa-solid fa-bell fa-xl');
							$('.js-push-button i').addClass('fa-solid fa-bell-slash fa-xl');
						}
					}

					messaging.onMessage(function (payload) {
						navigator.serviceWorker.register('/messaging-sw.js');
						Notification.requestPermission(function (result) {
							if (result === 'granted') {
								navigator.serviceWorker.ready.then(function (registration) {
									payload.notification.data = payload.notification;
									registration.showNotification(payload.notification.title, payload.notification);
								}).catch(function (error) {
									console.log('ServiceWorker registration failed', error);
								});
							}
						});
					});
				});
			</script>
        <?php
        endif; ?>
        <?php
        if (!Yii::app()->user->checkAccess('systemAdmin') and Yii::app()->params->use_rapid_msg == 1 and Yii::app()->user->checkAccess('allowAlertNotify') == 1): ?>
			<script>
				var deletes = setInterval(function () {
					$('#flash_msg').load('/msg/deleteall');
				}, 300000);
				var isplay  = "<?php if (Yii::app()->user->checkAccess('allowSoundNotify')) {
                    echo 1;
                } else {
                    echo 0;
                } ?>";
				if (isplay == '1') {
					var audio = document.getElementsByTagName('audio')[0];
				}

				function Messanger() {
					this.last       = 0;
					this.timeout    = 360;
					this.comet      = 0;
					this.deletes    = 0;
					var self        = this;
					this.putMessage = function (type, date, id, name, user, text, uid) {
						// callback, добавляет сообщения на страницу, вызывается из полученных с сервера данных
						self.last = id;
						var inner;
						var token = "<?php echo Yii::app()->request->csrfToken; ?>";
						if (type == 'call') {
							if (name) {
								inner = '<div class="alert alert-success"><button type="button" id="' + id + '" token="' + token + '" class="close call" data-dismiss="alert">&times;</button> <p style="font-size:18px; font-weight:bold; color:fff">Входящий звонок!</p><i class="icon icon-calendar"></i><b>Дата: ' + date + '</b><br/><i class="icon icon-user"></i><b>Вам звонит: ' + name + '</b><br/><i class="icon icon-phone"></i><b>Номер: ' + user + '</b><br/><i class="icon icon-building"></i><b>Компания: ' + text + '</b><br/><br/><a class="btn btn-info" href="/request/createfromcall?user=' + uid + '&call=' + id + '">Создать заявку</a>&nbsp;<a class="btn btn-danger" href="/calls/' + id + '">Карточка звонка</a></div>';
							} else {
								inner = '<div class="alert alert-success"><button type="button" id="' + id + '" token="' + token + '" class="close call" data-dismiss="alert">&times;</button> <p style="font-size:18px; font-weight:bold; color:fff">Входящий звонок!</p><i class="icon icon-calendar"></i><b>Дата: ' + date + '</b><br/><i class="icon icon-phone"></i><b>Номер: ' + user + '</b><br/><br/><a class="btn btn-danger" href="/calls/' + id + '">Карточка звонка</a></div>';
							}
						} else {
							inner = '<div class="alert alert-error"><button type="button" id="' + id + '" token="' + token + '" class="close" data-dismiss="alert">&times;</button> <b><a href="/request/viewsingle/?id=' + name + '&alert=' + id + '">[Ticket #' + name + ']</a></b> ' + text + '</div>';
						}
						var b       = document.createElement('div');
						b.innerHTML = inner;
						$('#flash_msg').append(b).fadeIn('slow');
						if ($.fn.yiiGridView) {
							$.fn.yiiGridView.update('request-grid');
						}
						if (isplay == '1') {
							audio.play();
						}

					}
					this.parseData  = function (message) {
						// простая обработка данных полученных с сервера, разбиваем строки и выполняет функции
						var items = message.split(';');
						var i     = 0;
						if (items.length < 1) return false;
						for (i; i < items.length; i++) {
							eval(items[i]);
						}
						setTimeout(self.connection, 1000);
					}
					this.connection = function () {
						// здесь открывается соединение с сервером
						self.comet = $.ajax({
							type:     'GET',
							url:      '/msg/comet',
							data:     {'id': self.last},
							dataType: 'text',
							timeout:  self.timeout * 1000,
							success:  self.parseData,
							error:    function () {
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
					$('.close').live('click', function () {
						var id   = $(this).attr('id');
						var csrf = $(this).attr('token');
						$.ajax({
							type:     'POST',
							url:      '/msg/delete',
							data:     {'id': id, 'YII_CSRF_TOKEN': csrf},
							dataType: 'text',
							cache:    false,
							error:    function (e) {
								console.log(e);
							}
						});
						return false;

					});
				});

				$(document).ready(function () {
					// инициализация
					$('.call').live('click', function () {
						var id   = $(this).attr('id');
						var csrf = $(this).attr('token');
						$.ajax({
							type:     'POST',
							url:      '/msg/deletecall',
							data:     {'id': id, 'YII_CSRF_TOKEN': csrf},
							dataType: 'text',
							cache:    false,
							error:    function (e) {
								console.log(e);
							}
						});
						return false;

					});
				});

			</script>
        <?php
        endif; ?>
		<script>
			$(document).ready(function () {
				// инициализация
				$('.sidebar-toggle').live('click', function () {
					var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
					$.ajax({
						type:     'POST',
						url:      '/site/toggle',
						data:     {'YII_CSRF_TOKEN': csrf},
						dataType: 'text',
						cache:    false,
						error:    function (e) {
							console.log(e);
						}
					});
					return false;
				});

				$('.user_li').click(function () {
					var sender = "<?php echo Yii::app()->user->id; ?>";
					var csrf   = "<?php echo Yii::app()->request->csrfToken; ?>";
					var user   = $(this).text();
					var usert  = user.replace(/\d/, '');
					$.ajax({
						type:     'POST',
						url:      '/chat/read',
						data:     {'sender': sender, 'user': usert, 'YII_CSRF_TOKEN': csrf},
						dataType: 'text',
						cache:    false,
						success:  function () {
							//document.location.href = '/chat/privates?user=main';
						}
					});
					return false;

				});
			});
		</script>
	</body>
</html>
