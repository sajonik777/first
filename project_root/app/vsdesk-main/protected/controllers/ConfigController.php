<?php

require __DIR__ . '/../vendors/telegram/autoload.php';
require __DIR__ . '/../vendors/viber/vendor/autoload.php';

use Telegram\Bot\Api;
use Viber\Client;

/**
 * Class ConfigController
 */
class ConfigController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//layouts/design3';


    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',// perform access control for CRUD operations
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['main', 'lic'],
                'roles' => ['mainSettings'],
            ],
            [
                'allow',
                'actions' => ['update', 'updateRun'],
                'roles' => ['shedulerSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => [
                    'getmail',
                    'getmailtest',
                    'getmailban',
                    'addban_item',
                    'deleteban_item',
                    'getmailview',
                    'getmailcreate',
                    'getmaildelete'
                ],
                'roles' => ['mailParserSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['push'],
                'roles' => ['mailParserSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => [
                    'ad',
                    'ajaxADTree',
                    'ADTest',
                    'LdapTest',
                    'ADUsersImport',
                    'ADComputersImport',
                    'adView',
                    'adCreate',
                    'adDelete'
                ],
                'roles' => ['adSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['sms'],
                'roles' => ['smsSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['request', 'selectgroup'],
                'roles' => ['ticketSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['appear'],
                'roles' => ['appearSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['attach'],
                'roles' => ['attachSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['attach'],
                'roles' => ['attachSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['ami'],
                'roles' => ['amiSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['tbot', 'tbottest', 'tbotremove'],
                'roles' => ['tbotSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['vbot', 'vbottest', 'vbotremove'],
                'roles' => ['vbotSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['slack'],
                'roles' => ['slackSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['widget', 'widgetgen'],
                'roles' => ['widgetSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['portal',],
                'roles' => ['portalSettings'],
            ],
            [
                'allow',// allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['tw', 'twtest', 'twtest2'],
                'roles' => ['twSettings'],
            ],
            [
                'allow',
                'actions' => ['jira'],
                'roles' => ['jiraSettings'],
            ],
            [
                'allow',
                'actions' => ['msbot', 'msbottest'],
                'roles' => ['msbotSettings'],
            ],
            [
                'allow',
                'actions' => ['whatsapp'],
                'roles' => ['wbotSettings'],
            ],
            [
                'deny',// deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Microsoft Bot Framework
     */
    public function actionMsbot()
    {
        $file_config = __DIR__ . '/../config/msbot.inc';
        $content = file_get_contents($file_config);
        $decode_content = base64_decode($content);
        if ($decode_content) {
            $config_arr = unserialize($decode_content);
        } else {
            $config_arr = [];
        }
        $model = new MSBotForm;
        $model->setAttributes($config_arr);
        if (isset($_POST['MSBotForm'])) {
            $config = [
                'enabled' => $_POST['MSBotForm']['enabled'],
                'appId' => $_POST['MSBotForm']['appId'],
                'appPassword' => $_POST['MSBotForm']['appPassword'],
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }

        $this->render('msbot', ['model' => $model]);
    }

    /**
     * Microsoft Bot Framework Test
     */
    public function actionMsbotTest()
    {
        if (isset($_POST['MSBotForm'])) {
            echo '<pre>';

            $appId = $_POST['MSBotForm']['appId'];
            $appPassword = $_POST['MSBotForm']['appPassword'];

            try {
                $client = new MicrosoftBotFramework($appId, $appPassword);
                if ($client->test()) {
                    echo "OK!\n";
                } else {
                    echo "Error!\n";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            echo '</pre>';
        }
    }

    /**
     * Jira Settings
     */
    public function actionJira()
    {
        $file_config = __DIR__ . '/../config/jira.inc';
        $content = file_get_contents($file_config);
        $decode_content = base64_decode($content);
        if ($decode_content) {
            $config_arr = unserialize($decode_content);
        } else {
            $config_arr = [];
        }
        $model = new JiraForm;
        $model->setAttributes($config_arr);
        if (isset($_POST['JiraForm'])) {
            $config = [
                'enabled' => $_POST['JiraForm']['enabled'],
                'user' => $_POST['JiraForm']['user'],
                'password' => $_POST['JiraForm']['password'],
                'project' => $_POST['JiraForm']['project'],
                'issuetype' => $_POST['JiraForm']['issuetype'],
                'services' => $_POST['JiraForm']['services'],
                'domen' => $_POST['JiraForm']['domen'],
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }

        $this->render('jira', ['model' => $model]);
    }

    /**
     *
     */
    public function actionPush()
    {
        $pushApiPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'pushApi.json';
        $pushApi = json_decode(file_get_contents($pushApiPath), true);
        if (isset($_POST['script_config']) and isset($_POST['api_key'])) {
            $pushApi['script_config'] = $_POST['script_config'];
            $pushApi['api_key'] = $_POST['api_key'];
            $json = json_encode($pushApi);
            if ($fh = fopen($pushApiPath, 'wb+')) {
                fwrite($fh, $json);
                fclose($fh);
            }
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('push', ['script_config' => $pushApi['script_config'], 'api_key' => $pushApi['api_key']]);
    }

    /**
     *
     */
    public function actionLic()
    {
        $oldlic = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'lic.inc';
        $newlic = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'license.php';
        if (file_exists($oldlic)) {
            $content7 = file_get_contents($oldlic);
            $arr7 = unserialize(base64_decode($content7));
        }

        if (isset($arr7) and $arr7 == true) {
            Yii::app()->user->setFlash('info',
                Yii::t('main-ui', 'Старый файл лицензии найден. Он будет конвертирован!'));
            if ($arr7['customer'] == 'DEMO') {
                $version = 'DEMO';
            } else {
                $version = 'CORP';
            }
            $newlic_content = "<?php
// Licensing constants for paid clients
define('redaction', '" . $version . "');
define('licensor', '" . $arr7['customer'] . "');
define('serial', '" . $arr7['license_number'] . "');
define('update_login', '" . $arr7['update_login'] . "');
define('update_password', '" . $arr7['update_pass'] . "');
define('support_date', 'Уточните у поставщика');";

            file_put_contents($newlic, $newlic_content);
            file_put_contents($oldlic, null);
            unlink($oldlic);

        }
        $this->render('lic');
    }

    /**
     *
     */
    public function actionAppear()
    {
        $file6 = dirname(__FILE__) . '../../config/appear.inc';
        $content6 = file_get_contents($file6);
        $arr6 = unserialize(base64_decode($content6));
        $model6 = new AppForm();
        $model6->setAttributes($arr6);
        if (isset($_POST['AppForm'])) {

            $config6 = [
                'fixedPanel' => $_POST['AppForm']['fixedPanel'],
                'showBtn' => $_POST['AppForm']['showBtn'],
                'brandName' => $_POST['AppForm']['brandName'],
                'loginText' => $_POST['AppForm']['loginText'],
                'mainLogo' => $_POST['AppForm']['mainLogo'],
                'smallLogo' => $_POST['AppForm']['smallLogo'],
                'theme' => $_POST['AppForm']['theme'],
                'portalHeader' => $_POST['AppForm']['portalHeader'],
                'portalText' => $_POST['AppForm']['portalText'],
            ];
            $str6 = base64_encode(serialize($config6));
            file_put_contents($file6, $str6);
            $model6->setAttributes($config6);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('appear', array('model6' => $model6));
    }

    /**
     *
     */
    public function actionPortal()
    {
        $file13 = dirname(__FILE__) . '../../config/portal.inc';
        $file_wrd = dirname(__FILE__) . '../../extensions/captchaExtended/words.ru.txt';
        $content13 = file_get_contents($file13);
        $content_wrd = file_get_contents($file_wrd);
        $arr13 = unserialize(base64_decode($content13));
        $model13 = new PortalForm();
        $model13->setAttributes($arr13);
        $model13->portalCaptchaWords = $content_wrd;
        if (isset($_POST['PortalForm'])) {

            $config13 = [
                'portalPhonebook' => $_POST['PortalForm']['portalPhonebook'],
                'portalAllowRegister' => $_POST['PortalForm']['portalAllowRegister'],
                'portalAllowRestore' => $_POST['PortalForm']['portalAllowRestore'],
                'portalAllowNews' => $_POST['PortalForm']['portalAllowNews'],
                'portalAllowKb' => $_POST['PortalForm']['portalAllowKb'],
                'portalAllowService' => $_POST['PortalForm']['portalAllowService'],
                'portalAllowCaptcha' => $_POST['PortalForm']['portalAllowCaptcha'],
                'portalCaptchaWords' => $_POST['PortalForm']['portalCaptchaWords'],

            ];
            $str13 = base64_encode(serialize($config13));
            file_put_contents($file13, $str13);
            file_put_contents($file_wrd, $_POST['PortalForm']['portalCaptchaWords']);
            $model13->setAttributes($config13);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('portal', ['model13' => $model13]);
    }

    /**
     *
     */
    public function actionAmi()
    {
        $file9 = __DIR__ . '../../config/ami.inc';
        $content9 = file_get_contents($file9);
        $arr9 = unserialize(base64_decode($content9));
        $model9 = new AsteriskForm();
        $model9->setAttributes($arr9);
        if (isset($_POST['AsteriskForm'])) {

            $config9 = [
                'amiEnabled' => $_POST['AsteriskForm']['amiEnabled'],
                'amiSendPush' => $_POST['AsteriskForm']['amiSendPush'],
                'amiHost' => $_POST['AsteriskForm']['amiHost'],
                'amiPort' => $_POST['AsteriskForm']['amiPort'],
                'amiScheme' => $_POST['AsteriskForm']['amiScheme'],
                'amiUsername' => $_POST['AsteriskForm']['amiUsername'],
                'amiSecret' => $_POST['AsteriskForm']['amiSecret'],
                'amiConnectTimeout' => $_POST['AsteriskForm']['amiConnectTimeout'],
                'amiReadTimeout' => $_POST['AsteriskForm']['amiReadTimeout'],
                'amiContext' => $_POST['AsteriskForm']['amiContext'],
                'amiChannel' => $_POST['AsteriskForm']['amiChannel'],
                'amiRecordPath' => $_POST['AsteriskForm']['amiRecordPath'],
//                'amiDBServer' => $_POST['AsteriskForm']['amiDBServer'],
//                'amiDBUser' => $_POST['AsteriskForm']['amiDBUser'],
//                'amiDBPassword' => $_POST['AsteriskForm']['amiDBPassword'],
            ];
            $model9->setAttributes($config9);
            if ($model9->validate()) {
                $str9 = base64_encode(serialize($config9));
                file_put_contents($file9, $str9);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }
        $this->render('ami', ['model9' => $model9]);
    }

    /**
     *
     */
    public function actionVbot()
    {
        $file_config = __DIR__ . '../../config/vbot.inc';
        $content = file_get_contents($file_config);
        $decode_content = base64_decode($content);
        $config_arr = unserialize($decode_content);
        $model = new VBotForm;
        $model->setAttributes($config_arr);
        if (isset($_POST['VBotForm'])) {
            $config = [
                'enabled' => $_POST['VBotForm']['enabled'],
                'token' => $_POST['VBotForm']['token'],
                'webhookUrl' => $_POST['VBotForm']['webhookUrl'],
                'msg' => $_POST['VBotForm']['msg'],
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }

        $this->render('vbot', ['model' => $model]);
    }

    /**
     *
     */
    public function actionTbot()
    {
        $file10 = dirname(__FILE__) . '../../config/tbot.inc';
        $content10 = file_get_contents($file10);
        $arr10 = unserialize(base64_decode($content10));
        $model10 = new TbotForm();
        $model10->setAttributes($arr10);
        if (isset($_POST['TbotForm'])) {
            $config10 = [
                'TBotEnabled' => $_POST['TbotForm']['TBotEnabled'],
                'TBotToken' => $_POST['TbotForm']['TBotToken'],
                'TBotURL' => $_POST['TbotForm']['TBotURL'],
                'TBotCertificate' => $_POST['TbotForm']['TBotCertificate'],
                'TBotMsg' => $_POST['TbotForm']['TBotMsg'],

            ];
            $model10->setAttributes($config10);
            if ($model10->validate()) {
                $str10 = base64_encode(serialize($config10));
                file_put_contents($file10, $str10);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }
        $this->render('tbot', ['model10' => $model10]);
    }

    /**
     *
     */
    public function actionSlack()
    {
        $file11 = dirname(__FILE__) . '../../config/slack.inc';
        $content11 = file_get_contents($file11);
        $arr11 = unserialize(base64_decode($content11));
        $model11 = new SlackForm();
        $model11->setAttributes($arr11);
        if (isset($_POST['SlackForm'])) {
            $config11 = [
                'SlackEnabled' => $_POST['SlackForm']['SlackEnabled'],
                'SlackUsername' => $_POST['SlackForm']['SlackUsername'],
                'SlackWebhookURL' => $_POST['SlackForm']['SlackWebhookURL'],
                'SlackIconURL' => $_POST['SlackForm']['SlackIconURL'],
                'SlackEmojii' => $_POST['SlackForm']['SlackEmojii'],
                'SlackTemplate' => $_POST['SlackForm']['SlackTemplate'],

            ];
            $model11->setAttributes($config11);
            if ($model11->validate()) {
                $str11 = base64_encode(serialize($config11));
                file_put_contents($file11, $str11);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }
        $this->render('slack', ['model11' => $model11]);
    }

    /**
     *
     */
    public function actionWidget()
    {
        $file12 = dirname(__FILE__) . '../../config/widget.inc';
        $content12 = file_get_contents($file12);
        $arr12 = unserialize(base64_decode($content12));
        $model12 = new WidgetForm();
        $model12->setAttributes($arr12);
        if (isset($_POST['WidgetForm'])) {
            $config12 = [
                'WidgetEnabled' => $_POST['WidgetForm']['WidgetEnabled'],
                'WidgetAnimate' => $_POST['WidgetForm']['WidgetAnimate'],
                'WidgetFiles' => $_POST['WidgetForm']['WidgetFiles'],
                'WidgetService' => $_POST['WidgetForm']['WidgetService'],
                'WidgetColor' => $_POST['WidgetForm']['WidgetColor'],
                'WidgetPosition' => $_POST['WidgetForm']['WidgetPosition'],
                'WidgetHeader' => $_POST['WidgetForm']['WidgetHeader'],
                'WidgetCode' => $_POST['WidgetForm']['WidgetCode'],
                'WidgetShowPersonal' => $_POST['WidgetForm']['WidgetShowPersonal'],

            ];
            $model12->setAttributes($config12);
            if ($model12->validate()) {
                $str12 = base64_encode(serialize($config12));
                file_put_contents($file12, $str12);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }
        $this->render('widget', ['model12' => $model12]);
    }

    /**
     *
     */
    public function actionWidgetGen()
    {
        if (isset($_POST['WidgetForm'])) {
            $animate = ($_POST['WidgetForm']['WidgetAnimate'] == 1) ? 'true' : 'false';
            $code =
                "<script>
    var univefWidget = {
    color: '" . $_POST['WidgetForm']['WidgetColor'] . "',
    animate: '" . $animate . "',
    position: '" . $_POST['WidgetForm']['WidgetPosition'] . "',
    url: '" . Yii::app()->params['homeUrl'] . "'
};
(function () {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = '" . Yii::app()->params['homeUrl'] . "/widget/widget.js';
    document.getElementsByTagName('head')[0].appendChild(s);
})();
</script>";
        }
        echo $code;
    }

    /**
     *
     */
    public function actionVbotTest()
    {
        if (isset($_POST['VBotForm'])) {
            echo '<pre>';

            $apiKey = $_POST['VBotForm']['token'];
            $webhookUrl = $_POST['VBotForm']['webhookUrl'];

            try {
                $client = new Client(['token' => $apiKey]);
                $client->setWebhook($webhookUrl);
                echo "Webhook was set!\n";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
            echo '</pre>';
        }
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function actionTbotTest()
    {
        if (isset($_POST['TbotForm'])) {
            echo '<pre>';
            $telegram = new Api($_POST['TbotForm']['TBotToken']);
            if (isset($_POST['TbotForm']['TBotCertificate']) and !empty($_POST['TbotForm']['TBotCertificate'])) {
                $response = $telegram->setWebhook([
                    'url' => $_POST['TbotForm']['TBotURL'],
                    'certificate' => $_POST['TbotForm']['TBotCertificate']
                ]);
            } else {
                $response = $telegram->setWebhook(['url' => $_POST['TbotForm']['TBotURL']]);
            }
            if ($response == true) {
                echo 'Webhook was set';
            } else {
                $response['description'];
            }
        }

    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function actionTbotRemove()
    {
        if (isset($_POST['TbotForm'])) {
            echo '<pre>';
            $telegram = new Api($_POST['TbotForm']['TBotToken']);
            $response = $telegram->removeWebhook();
            if ($response == true) {
                echo 'Webhook was removed';
            } else {
                $response['description'];
            }
        }

    }

    /**
     *
     */
    public function actionTw()
    {
        $file_config = __DIR__ . '../../config/teamviewer.inc';
        $content = file_get_contents($file_config);
        $decode_content = base64_decode($content);
        $config_arr = unserialize($decode_content);
        $model = new TeamViewerForm;
        $model->setAttributes($config_arr);
        if (isset($_POST['TeamViewerForm'])) {
            $config = [
                'enabled' => $_POST['TeamViewerForm']['enabled'],
                'client_id' => $_POST['TeamViewerForm']['client_id'],
                'client_secret' => $_POST['TeamViewerForm']['client_secret'],
                'access_token' => $_POST['TeamViewerForm']['access_token'],
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }

        $this->render('tw', ['model' => $model]);
    }

    /**
     * @throws CException
     */
    public function actionTwtest()
    {
        if (isset($_GET['client_id']) && isset($_GET['client_secret'])) {
            Yii::app()->session['client_id'] = $_GET['client_id'];
            Yii::app()->session['client_secret'] = $_GET['client_secret'];
            $tw_api = new TeamViewer;
            $url = $tw_api->createUrl($_GET['client_id'], Yii::app()->params['homeUrl'] . '/config/twtest');
            $this->redirect($url);
        }
        if (isset($_GET['code'])) {
            $client_id = Yii::app()->session['client_id'];
            $client_secret = Yii::app()->session['client_secret'];
            $tw_api = new TeamViewer;
            $access_token = $tw_api->createAccessToken($_GET['code'], $client_id, $client_secret);
            $this->layout = null;
            $this->renderPartial('twtest', ['access_token' => $access_token]);
        }
    }

    /**
     * @throws CException
     */
    public function actionTwtest2()
    {
        if (isset($_GET['client_id']) && isset($_GET['client_secret'])) {
            Yii::app()->session['client_id'] = $_GET['client_id'];
            Yii::app()->session['client_secret'] = $_GET['client_secret'];
            $tw_api = new TeamViewer;
            $url = $tw_api->createUrl($_GET['client_id'], Yii::app()->params['homeUrl'] . '/config/twtest2');
            $this->redirect($url);
        }
        if (isset($_GET['code'])) {
            $client_id = Yii::app()->session['client_id'];
            $client_secret = Yii::app()->session['client_secret'];
            $tw_api = new TeamViewer;
            $access_token = $tw_api->createAccessToken($_GET['code'], $client_id, $client_secret);
            $file_config = __DIR__ . '../../config/teamviewer.inc';
            $content = file_get_contents($file_config);
            $decode_content = base64_decode($content);
            $config_arr = unserialize($decode_content);
            $model = new TeamViewerForm;
            $model->setAttributes($config_arr);
            $model->access_token = $access_token;
            $config = [
                'enabled' => $model->enabled,
                'client_id' => $model->client_id,
                'client_secret' => $model->client_secret,
                'access_token' => $access_token,
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                echo "<script>
                    window.onunload = refreshParent;
                    function refreshParent() {
                        window.opener.location.reload();
                    }
                    window.onload = function () {
                        window.close();
                    };
                </script>";
            }
        }
    }

    /**
     *
     */
    public function actionRequest()
    {
        $file5 = dirname(__FILE__) . '../../config/request.inc';
        $content5 = file_get_contents($file5);
        $arr5 = unserialize(base64_decode($content5));
        $model5 = new RequestForm();
        $model5->setAttributes($arr5);
        if (isset($_POST['RequestForm'])) {
            $config5 = [
                'enabled' => $_POST['RequestForm']['enabled'],
                'zdpriority' => $_POST['RequestForm']['zdpriority'],
                'zdsla' => $_POST['RequestForm']['zdsla'],
                'zdcategory' => $_POST['RequestForm']['zdcategory'],
                'zdtype' => $_POST['RequestForm']['zdtype'],
                'zdmanager' => $_POST['RequestForm']['zdmanager'],
                'update_grid' => $_POST['RequestForm']['update_grid'],
                'update_grid_timeout' => $_POST['RequestForm']['update_grid_timeout'],
                'grid_items' => $_POST['RequestForm']['grid_items'],
                't_filter' => $_POST['RequestForm']['t_filter'],
                'monopoly' => $_POST['RequestForm']['monopoly'],
                'kbcategory' => $_POST['RequestForm']['kbcategory'],
                'autoaccept' => $_POST['RequestForm']['autoaccept'],
                'nocomment' => $_POST['RequestForm']['nocomment'],
                'autoarch' => $_POST['RequestForm']['autoarch'],
                'autoarchdays' => $_POST['RequestForm']['autoarchdays'],
                'req_columns_default' => $_POST['RequestForm']['req_columns_default'],
            ];
            $str5 = base64_encode(serialize($config5));
            file_put_contents($file5, $str5);
            $model5->setAttributes($config5);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('request', ['model5' => $model5]);
    }

    /**
     *
     */
    public function actionSms()
    {
        $file4 = dirname(__FILE__) . '../../config/sms.inc';
        $content4 = file_get_contents($file4);
        $arr4 = unserialize(base64_decode($content4));
        $model4 = new SmsForm();
        $model4->setAttributes($arr4);
        if (isset($_POST['SmsForm'])) {
            $config4 = [
                'api_id' => $_POST['SmsForm']['api_id'],
                'smsuser' => $_POST['SmsForm']['smsuser'],
                'smspassword' => $_POST['SmsForm']['smspassword'],
                'smsformat' => $_POST['SmsForm']['smsformat'] ? $_POST['SmsForm']['smsformat'] : 0,
                'smssender' => $_POST['SmsForm']['smssender'],
            ];
            $str4 = base64_encode(serialize($config4));
            file_put_contents($file4, $str4);
            $model4->setAttributes($config4);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('sms', ['model4' => $model4]);
    }

    public function actionAd()
    {
        $configDirPath = dirname(__FILE__) . '/../config/';
        $mask = $configDirPath . 'ad*.inc';
        $adIncFiles = [];
        foreach (glob($mask) as $filename) {
            $fArr = explode('/', $filename);
            $content = file_get_contents($filename);
            $confArr = unserialize(base64_decode($content));
            $confArr['fileName'] = $filename;
            $confArr['id'] = end($fArr);
            $adIncFiles[] = $confArr;
        }

        $dataProvider = new CArrayDataProvider($adIncFiles);
        Yii::app()->user->setFlash('danger', Yii::t('main-ui',
            '<strong>Warning!</strong><br/> To integrate with LDAP, you must create security groups that should have a name similar to the roles in Univef service desk. For example "univefuser", "univefmanager" and "univefadmin" for the default role. Enable these groups of users so that each user was only in one group univef, after inclusion in the group, the user will receive a role is selected for authorization in Univef service desk.'));
        $this->render('adlist', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $file
     */
    public function actionAdView($file)
    {
        $file3 = __DIR__ . '../../config/' . $file;
        $content3 = file_get_contents($file3);
        $arr3 = unserialize(base64_decode($content3));

        $model3 = new AdForm();
        $ldap_model = new OpenLDAPForm();

        if (isset($arr3['type']) && 'openldap' === $arr3['type']) {
            $ldap_model->setAttributes($arr3);
        } else {
            $model3->setAttributes($arr3);
        }

        if (isset($_POST['AdForm'])) {
            $config3 = [
                'type' => $_POST['AdForm']['type'],
                'ad_enabled' => $_POST['AdForm']['ad_enabled'],
                'basedn' => $_POST['AdForm']['basedn'],
                'accountSuffix' => $_POST['AdForm']['accountSuffix'],
                'domaincontrollers' => $_POST['AdForm']['domaincontrollers'],
                'adminusername' => $_POST['AdForm']['adminusername'],
                'adminpassword' => $_POST['AdForm']['adminpassword'],
                'fastAuth' => $_POST['AdForm']['fastAuth'],
            ];
            $str3 = base64_encode(serialize($config3));
            file_put_contents($file3, $str3);
            $model3->setAttributes($config3);
            $this->redirect(['ad']);
        }

        if (isset($_POST['OpenLDAPForm'])) {
            $config3 = [
                'ad_enabled' => $_POST['OpenLDAPForm']['ad_enabled'],
                'type' => $_POST['OpenLDAPForm']['type'],
                'host' => $_POST['OpenLDAPForm']['host'],
                'account' => $_POST['OpenLDAPForm']['account'],
                'password' => $_POST['OpenLDAPForm']['password'],
                'baseDN' => $_POST['OpenLDAPForm']['baseDN'],
                'usersDN' => $_POST['OpenLDAPForm']['usersDN'],
                'groupsDN' => $_POST['OpenLDAPForm']['groupsDN'],
                'accountSuffix' => $_POST['OpenLDAPForm']['accountSuffix'],
            ];
            $str3 = base64_encode(serialize($config3));
            file_put_contents($file3, $str3);
            $model3->setAttributes($config3);
            $this->redirect(['ad']);
        }

        $this->render('ad', ['model3' => $model3, 'ldap_model' => $ldap_model, 'file' => $file]);
    }

    /**
     *
     */
    public function actionAdCreate()
    {
        $model3 = new AdForm();
        $ldap_model = new OpenLDAPForm();
        if (isset($_POST['AdForm'])) {

            $configDirPath = dirname(__FILE__) . '/../config/';
            $mask = $configDirPath . 'ad*.inc';
            $adIncFiles = [];
            $dataList = [];
            foreach (glob($mask) as $filename) {
                $fArr = explode('.', $filename);
                $temp = $fArr[(count($fArr)) - 2];
                if (is_numeric($temp)) {
                    $adIncFiles[] = $temp;
                }
                $content = file_get_contents($filename);
                $confArr = unserialize(base64_decode($content));
                $dataList[] = $confArr['accountSuffix'];
            }

            $config3 = [
                'type' => $_POST['AdForm']['type'],
                'ad_enabled' => $_POST['AdForm']['ad_enabled'],
                'basedn' => $_POST['AdForm']['basedn'],
                'accountSuffix' => $_POST['AdForm']['accountSuffix'],
                'domaincontrollers' => $_POST['AdForm']['domaincontrollers'],
                'adminusername' => $_POST['AdForm']['adminusername'],
                'adminpassword' => $_POST['AdForm']['adminpassword'],
                'fastAuth' => $_POST['AdForm']['fastAuth'],
            ];
            $model3->setAttributes($config3);
            if (in_array($_POST['AdForm']['accountSuffix'], $dataList)) {
                Yii::app()->user->setFlash('error', Yii::t('main-ui', 'Такой домен уже существует.'));
            } else {
                $fileNumber = 1;
                if (count($adIncFiles) != 0) {
                    sort($adIncFiles);
                    $fileNumber = (int)end($adIncFiles);
                    $fileNumber++;
                }
                if (is_file($configDirPath . 'ad.inc')) {
                    $file3 = dirname(__FILE__) . '../../config/ad.' . $fileNumber . '.inc';
                } else {
                    $file3 = dirname(__FILE__) . '../../config/ad.inc';
                }

                $str3 = base64_encode(serialize($config3));
                file_put_contents($file3, $str3);

                $this->redirect(['ad']);
            }
        }

        if (isset($_POST['OpenLDAPForm'])) {

            $configDirPath = dirname(__FILE__) . '/../config/';
            $mask = $configDirPath . 'ad*.inc';
            $adIncFiles = [];
            $dataList = [];
            foreach (glob($mask) as $filename) {
                $fArr = explode('.', $filename);
                $temp = $fArr[(count($fArr)) - 2];
                if (is_numeric($temp)) {
                    $adIncFiles[] = $temp;
                }
                $content = file_get_contents($filename);
                $confArr = unserialize(base64_decode($content));
                $dataList[] = $confArr['accountSuffix'];
            }

            $config3 = [
                'ad_enabled' => $_POST['OpenLDAPForm']['ad_enabled'],
                'type' => $_POST['OpenLDAPForm']['type'],
                'host' => $_POST['OpenLDAPForm']['host'],
                'account' => $_POST['OpenLDAPForm']['account'],
                'password' => $_POST['OpenLDAPForm']['password'],
                'baseDN' => $_POST['OpenLDAPForm']['baseDN'],
                'usersDN' => $_POST['OpenLDAPForm']['usersDN'],
                'groupsDN' => $_POST['OpenLDAPForm']['groupsDN'],
                'accountSuffix' => $_POST['OpenLDAPForm']['accountSuffix'],
            ];
            $model3->setAttributes($config3);
            if (in_array($_POST['OpenLDAPForm']['accountSuffix'], $dataList)) {
                Yii::app()->user->setFlash('error', Yii::t('main-ui', 'Такой домен уже существует.'));
            } else {
                $fileNumber = 1;
                if (count($adIncFiles) != 0) {
                    sort($adIncFiles);
                    $fileNumber = (int)end($adIncFiles);
                    $fileNumber++;
                }

                $file3 = dirname(__FILE__) . '../../config/ad.' . $fileNumber . '.inc';
                $str3 = base64_encode(serialize($config3));
                file_put_contents($file3, $str3);

                $this->redirect(['ad']);
            }
        }

        $this->render('ad', ['model3' => $model3, 'ldap_model' => $ldap_model]);
    }

    /**
     * @param $file
     */
    public function actionAdDelete($file)
    {
        if ($file != 'ad.inc') {
            $file3 = dirname(__FILE__) . '/../config/' . $file;
            unlink($file3);
        }
        $this->redirect(array('ad'));
    }

    /**
     *
     */
    public function actionGetmail()
    {
        $configDirPath = dirname(__FILE__) . '/../config/';
        $mask = $configDirPath . 'getmail*.inc';
        $getmailIncFiles = [];
        foreach (glob($mask) as $filename) {
            $fArr = explode('/', $filename);
            $content = file_get_contents($filename);
            $confArr = unserialize(base64_decode($content));
            $confArr['fileName'] = $filename;
            $confArr['id'] = end($fArr);
            $getmailIncFiles[] = $confArr;
        }
        $dataProvider = new CArrayDataProvider($getmailIncFiles);
        $this->render('getmaillist', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $file
     */
    public function actionGetmailView($file)
    {
        $file2 = dirname(__FILE__) . '../../config/' . $file;
        $content2 = file_get_contents($file2);
        $arr2 = unserialize(base64_decode($content2));
        $model2 = new GetmailForm();
        $model2->setAttributes($arr2);
        if (isset($_POST['GetmailForm'])) {
            $config2 = [
                'getmail_enabled' => $_POST['GetmailForm']['getmail_enabled'],
                'getmailitems' => $_POST['GetmailForm']['getmailitems'],
                'getmailserver' => $_POST['GetmailForm']['getmailserver'],
                'getmailport' => $_POST['GetmailForm']['getmailport'],
                'getmailpath' => $_POST['GetmailForm']['getmailpath'],
                'getmailuser' => $_POST['GetmailForm']['getmailuser'],
                'getmailpass' => $_POST['GetmailForm']['getmailpass'],
                'getmaildelete' => $_POST['GetmailForm']['getmaildelete'],
                'getmailservice' => $_POST['GetmailForm']['getmailservice'],
                'getmaildisableauth' => $_POST['GetmailForm']['getmaildisableauth'],
                'getmaildisableconvert' => $_POST['GetmailForm']['getmaildisableconvert'],
                'getmaildisablenl2br' => $_POST['GetmailForm']['getmaildisablenl2br'],
                'getmaildisabletrim' => $_POST['GetmailForm']['getmaildisabletrim'],
                'getmaildisablectrim' => $_POST['GetmailForm']['getmaildisablectrim'],
                'getmailclosedtonew' => $_POST['GetmailForm']['getmailclosedtonew'],
                'getmailcopytowatchers' => $_POST['GetmailForm']['getmailcopytowatchers'],

                'getmailsmsec' => $_POST['GetmailForm']['getmailsmsec'],
                'getmailsmdebug' => $_POST['GetmailForm']['getmailsmdebug'],
                'getmailsmhost' => $_POST['GetmailForm']['getmailsmhost'],
                'getmailsmport' => $_POST['GetmailForm']['getmailsmport'],
                'getmailsmqueue' => $_POST['GetmailForm']['getmailsmqueue'],
                'getmailsmignoressl' => $_POST['GetmailForm']['getmailsmignoressl'],
                'getmailsmtpauth' => $_POST['GetmailForm']['getmailsmtpauth'],
                'getmailsmusername' => $_POST['GetmailForm']['getmailsmusername'],
                'getmailsmpassword' => $_POST['GetmailForm']['getmailsmpassword'],
                'getmailsmfrom' => $_POST['GetmailForm']['getmailsmfrom'],
                'getmailsmfromname' => $_POST['GetmailForm']['getmailsmfromname'],
            ];
            $str2 = base64_encode(serialize($config2));
            file_put_contents($file2, $str2);
            $model2->setAttributes($config2);
            $this->redirect(['getmail']);
        }
        $this->render('getmail', ['model2' => $model2, 'file' => $file]);
    }

    /**
     *
     */
    public function actionGetmailCreate()
    {
        $model2 = new GetmailForm();
        if (isset($_POST['GetmailForm'])) {

            $configDirPath = dirname(__FILE__) . '/../config/';
            $mask = $configDirPath . 'getmail*.inc';
            $getmailIncFiles = [];
            foreach (glob($mask) as $filename) {
                $fArr = explode('.', $filename);
                $temp = $fArr[(count($fArr)) - 2];
                if (is_numeric($temp)) {
                    $getmailIncFiles[] = $temp;
                }
            }

            $config2 = [
                'getmail_enabled' => $_POST['GetmailForm']['getmail_enabled'],
                'getmailitems' => $_POST['GetmailForm']['getmailitems'],
                'getmailserver' => $_POST['GetmailForm']['getmailserver'],
                'getmailport' => $_POST['GetmailForm']['getmailport'],
                'getmailpath' => $_POST['GetmailForm']['getmailpath'],
                'getmailuser' => $_POST['GetmailForm']['getmailuser'],
                'getmailpass' => $_POST['GetmailForm']['getmailpass'],
                'getmaildelete' => $_POST['GetmailForm']['getmaildelete'],
                'getmailservice' => $_POST['GetmailForm']['getmailservice'],
                'getmaildisableauth' => $_POST['GetmailForm']['getmaildisableauth'],
                'getmaildisableconvert' => $_POST['GetmailForm']['getmaildisableconvert'],
                'getmaildisablenl2br' => $_POST['GetmailForm']['getmaildisablenl2br'],
                'getmaildisabletrim' => $_POST['GetmailForm']['getmaildisabletrim'],
                'getmaildisablectrim' => $_POST['GetmailForm']['getmaildisablectrim'],
            ];

            $model2->setAttributes($config2);
            $fileNumber = 1;
            if (count($getmailIncFiles) != 0) {
                sort($getmailIncFiles);
                $fileNumber = (int)end($getmailIncFiles);
                $fileNumber++;
            }

            $file2 = dirname(__FILE__) . '../../config/getmail.' . $fileNumber . '.inc';
            $str2 = base64_encode(serialize($config2));
            file_put_contents($file2, $str2);

            $this->redirect(['getmail']);

        }
        $this->render('getmail', ['model2' => $model2]);
    }

    /**
     * @param $file
     */
    public function actionGetmailDelete($file)
    {
        if ($file != 'getmail.inc') {
            $file2 = dirname(__FILE__) . '/../config/' . $file;
            unlink($file2);
        }
        $this->redirect(['getmail']);
    }

    /**
     *
     */
    public function actionGetmailban()
    {
        $model = Banlist::model()->findAll();
        $this->render('getmailban', ['model' => $model]);
    }

    /**
     * @throws CHttpException
     */
    public function actionaddban_item()
    {
        $model = new Banlist;
        if (isset($_POST['value'])) {
            if ($model->validate()) {
                $model->value = $_POST['value'];
                if ($model->save()) {
                    $this->redirect('getmailban');
                }
            }
            if (!$model->validate()) {
                //$this->redirect('getmailban');
                throw new CHttpException(400,
                    Yii::t('main-ui', 'Invalid email address or email already exist, please insert valid email!'));
            }
        }
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actiondeleteban_item($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            Banlist::model()->findByPk($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['getmailban']);
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     *
     */
    public function actionGetmailTest()
    {
        if (isset($_POST['GetmailForm'])) {
            echo '<pre>';
            $model = new GetmailForm();
            $model->setAttributes($_POST['GetmailForm']);

            $ret = false;
            if ($model->getmaildisableauth) {
                $ret = @imap_open('{' . $model->getmailserver . ':' . $model->getmailport . $model->getmailpath . '}INBOX',
                    $model->getmailuser, $model->getmailpass, null, 1,
                    array('DISABLE_AUTHENTICATOR' => 'GSSAPI')) or die(imap_last_error());
            } else {
                $ret = @imap_open('{' . $model->getmailserver . ':' . $model->getmailport . $model->getmailpath . '}INBOX',
                    $model->getmailuser, $model->getmailpass) or die(imap_last_error());
            }

            if ($ret == false) {
                echo 'Ошибка';
            } else {
                echo 'Успешно';
            }
        }
    }

    /**
     *
     */
    public function actionMain()
    {
        $lang = [];
        $lang_dir = dirname(__FILE__) . '/../messages/';
        $list = $this->myscandir($lang_dir, 0);
        foreach ($list as $key => $value) {
            $lang[$value] = Yii::t('main-ui', $value);
        }
        $file = dirname(__FILE__) . '../../config/params.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $model = new ConfigForm();
        $model->setAttributes($arr);
        if (isset($_POST['ConfigForm'])) {
            $config = [
                'use_rapid_msg' => $_POST['ConfigForm']['use_rapid_msg'],
                'allow_register' => $_POST['ConfigForm']['allow_register'],
                'allow_select_company' => $_POST['ConfigForm']['allow_select_company'],
                'homeUrl' => $_POST['ConfigForm']['homeUrl'],
                'redirectUrl' => $_POST['ConfigForm']['redirectUrl'],
                'adminEmail' => $_POST['ConfigForm']['adminEmail'],
                'pageHeader' => $_POST['ConfigForm']['pageHeader'],
                'smsec' => $_POST['ConfigForm']['smsec'],
                'smdebug' => $_POST['ConfigForm']['smdebug'],
                'smignoressl' => $_POST['ConfigForm']['smignoressl'],
                'smhost' => $_POST['ConfigForm']['smhost'],
                'smport' => $_POST['ConfigForm']['smport'],
                'smqueue' => $_POST['ConfigForm']['smqueue'],
                'smtpauth' => $_POST['ConfigForm']['smtpauth'],
                'smusername' => $_POST['ConfigForm']['smusername'],
                'smpassword' => $_POST['ConfigForm']['smpassword'],
                'smfrom' => $_POST['ConfigForm']['smfrom'],
                'smfromname' => $_POST['ConfigForm']['smfromname'],
                'languages' => $_POST['ConfigForm']['languages'],
                'timezone' => $_POST['ConfigForm']['timezone'],
                'useiframe' => 0,
                'allowportal' => $_POST['ConfigForm']['allowportal'],
            ];
            $str = base64_encode(serialize($config));
            file_put_contents($file, $str);
            Yii::app()->user->setFlash('config', Yii::t('main-ui', 'All preferences saved successfully'));
            $model->setAttributes($config);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('main', ['model' => $model, 'list' => $lang]);
    }

    /**
     * @param $dir
     * @param int $sort
     * @return array|bool
     */
    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);

        // если директории не существует
        if (!$list) {
            return false;
        }

        // удаляем . и .. (я думаю редко кто использует)
        if ($sort == 0) {
            unset($list[0], $list[1]);
        } else {
            unset($list[count($list) - 1], $list[count($list) - 1]);
        }
        return $list;
    }

    /**
     *
     */
    public function actionSelectGroup()
    {
        $model5 = new RequestForm();
        if ($_POST['RequestForm']['zdtype'] == 1) {
            $user_list = CUsers::all();
            $name = Yii::t('main-ui', 'Manager');
            $mod = 'zdmanager';
        } else {
            $userlist = Groups::model()->findAll();
            $user_list = CHtml::listData($userlist, 'name', 'name');
            $name = Yii::t('main-ui', 'Group');
            $mod = 'zdmanager';
        }
        echo CHtml::activeLabelEx($model5, $mod);
        echo CHtml::activeDropDownList($model5, $mod, $user_list, ['class' => 'span12']);
    }

    /**
     *
     */
    public function actionAttach()
    {
        $file8 = dirname(__FILE__) . '../../config/attach.inc';
        $content8 = file_get_contents($file8);
        $arr8 = unserialize(base64_decode($content8));
        $model8 = new AttachForm();
        $model8->setAttributes($arr8);
        if (isset($_POST['AttachForm'])) {

            $config8 = [
                'extensions' => $_POST['AttachForm']['extensions'],
                'duplicate_message' => $_POST['AttachForm']['duplicate_message'],
                'denied_message' => $_POST['AttachForm']['denied_message'],
                'max_file_size' => $_POST['AttachForm']['max_file_size'],
                'max_file_msg' => $_POST['AttachForm']['max_file_msg'],
            ];
            $str8 = base64_encode(serialize($config8));
            file_put_contents($file8, $str8);
            $model8->setAttributes($config8);
            Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
        }
        $this->render('attach', ['model8' => $model8]);
    }

    /**
     *
     */
    public function actionADTest()
    {
        if (isset($_POST['AdForm'])) {
            echo '<pre>';
            $model = new AdForm();
            $model->setAttributes($_POST['AdForm']);

            $ret = false;
            $ad = [
                'ad_enabled' => 1,
                'baseDn' => $model->basedn,
                'accountSuffix' => $model->accountSuffix,
                'domainControllers' => [$model->domaincontrollers],
                'adminUsername' => $model->adminusername,
                'adminPassword' => $model->adminpassword,
            ];
            try {
                $ldap = new LdapComponent($ad);
                $ldap->ad_enabled = 1;
                $ldap->baseDn = $model->basedn;
                $ldap->accountSuffix = $model->accountSuffix;
                $ldap->domainControllers = [$model->domaincontrollers];
                $ldap->adminUsername = $model->adminusername;
                $ldap->adminPassword = $model->adminpassword;
                @$ldap->init();
                $ret = @$ldap->connect();
            } catch (Exception $e) {
                echo 'Ошибка: ', $e->getMessage(), "\n";
            }
            if ($ret != false) {
                echo 'Успешно';
            }
        }
    }

    /**
     *
     */
    public function actionLdapTest()
    {
        if (isset($_POST['OpenLDAPForm'])) {
            echo '<pre>';
            $model = new OpenLDAPForm();
            $model->setAttributes($_POST['OpenLDAPForm']);

            $ret = false;
            $ad = [
                'ad_enabled' => 1,
                'host' => $model->host,
                'account' => $model->account,
                'password' => [$model->password],
                'baseDN' => $model->baseDN,
                'usersDN' => $model->usersDN,
                'groupsDN' => $model->groupsDN,
            ];
            try {
                $ldap = new OpenLdapComponent($ad);
                $ldap->ad_enabled = 1;
                $ldap->host = $model->host;
                $ldap->account = $model->account;
                $ldap->password = $model->password;
                $ldap->baseDN = $model->baseDN;
                $ldap->usersDN = $model->usersDN;
                $ldap->groupsDN = $model->groupsDN;
                $ldap->init();
                $ret = $ldap->connect();
            } catch (Exception $e) {
                echo 'Ошибка: ', $e->getMessage(), "\n";
            }
            if ($ret != false) {
                echo 'Успешно';
            } else {
                echo 'Ошибка';
            }
        }
    }

    /**
     *
     */
    public function actionAjaxADTree()
    {
        $ldap = Yii::app()->ldap;
        $parent = null;
        if (isset($_GET['root']) and $_GET['root'] != 'source') {
            $parent = $_GET['root'];
            $parent = explode('|', $parent);
        }

        $groups1 = @$ldap->folder()->listing($parent, adLDAP::ADLDAP_FOLDER, false, 'folder');
        $groups2 = @$ldap->folder()->listing($parent, adLDAP::ADLDAP_FOLDER, false, 'container');

        if (is_array($groups1) and is_array($groups2)) {
            $groups = array_merge($groups1, $groups2);
        } elseif (is_array($groups1) and !is_array($groups2)) {
            $groups = $groups1;
        } elseif (!is_array($groups1) and is_array($groups2)) {
            $groups = $groups2;
        } else {
            $text = null;
        }

        $childrens = [];
        if (isset($groups)) {
            foreach ($groups as $group) {
                //echo $group['dn'].'<br>';
                $dns = explode(',', $group['dn']);
                $text = null;
                foreach ($dns as $dn) {
                    if (substr($dn, 0, 2) == 'OU') {
                        $text = substr($dn, 3);
                        break;
                    }
                    if (substr($dn, 0, 2) == 'CN') {
                        $text = substr($dn, 3);
                        break;
                    }
                }
                if ($text != null) {
                    if (count($parent) != 0) {
                        $_check_text = "<input type='checkbox' value='" . $text . '|' . (implode('|',
                                $parent)) . "' name='ImportGroups[" . $text . "]'>" . $text;
                        $childrens[] = [
                            'text' => $_check_text,
                            'id' => $text . '|' . (implode('|', $parent)),
                            'hasChildren' => true,
                        ];
                    } else {
                        $_check_text = "<input type='checkbox' value='" . $text . "' name='ImportGroups[" . $text . "]'>" . $text;
                        $childrens[] = array('text' => $_check_text, 'id' => $text, 'hasChildren' => true,);
                    }
                }

            }
        }

        if ($parent == null) {
            $my_data = [
                [
                    'text' => Yii::t('main-ui', 'Select OU from AD tree'),
                    'expanded' => true,
                    'children' => $childrens,
                ],
            ];
        } else {
            $my_data = $childrens;
        }

        echo CTreeView::saveDataAsJson($my_data);
        exit();
    }

    /**
     *
     */
    public function actionADUsersImport()
    {
        if (isset($_POST['ImportGroups']) and !empty($_POST['ImportGroups'])) {

            $users_update = 0;
            $users_create = 0;

            $ldap = Yii::app()->ldap;

            foreach ($_POST['ImportGroups'] as $group => $val) {
                $users = $ldap->folder()->listing(explode('|', $val), adLDAP::ADLDAP_FOLDER, null, 'user');
                foreach ($users as $user) {
                    if (!empty($user['samaccountname'][0])) {
                        $user = $ldap->user()->info($user['samaccountname'][0], [
                            "samaccountname",
                            "mail",
                            "memberof",
                            "department",
                            "displayname",
                            "telephonenumber",
                            "primarygroupid",
                            "objectsid",
                            "description",
                            "title",
                            "company",
                            "manager",
                            "physicalDeliveryOfficeName",
                            "userAccountControl"
                        ]);
                        if (isset($user[0]['samaccountname'][0]) and isset($user[0]['displayname'][0]) and ($user[0]['useraccountcontrol'][0] !== '66050')) {
                            if ((isset($user[0]['mail'][0]) and !empty($user[0]['mail'][0])) or $_POST['ADUsersImport']['notemailusers'] == 1) {

                                $userFromDB = CUsers::model()->find('LOWER(Username)=?',
                                    array(strtolower($user[0]['samaccountname'][0])));
                                preg_match('|CN=(.+?),OU|sei', $user[0]['manager'][0], $arr);
                                $manager_name = $arr[1];
                                if (!$userFromDB) {
                                    $dbUser = new CUsers;
                                    $dbUser->Username = $user[0]['samaccountname'][0];
                                    $dbUser->Password = md5('mdy65wtc76' . 'As123456');
                                    $dbUser->fullname = $user[0]['displayname'][0];
                                    $dbUser->Email = isset($user[0]['mail'][0]) ? $user[0]['mail'][0] : null;
                                    $dbUser->Phone = isset($user[0]['telephonenumber'][0]) ? $user[0]['telephonenumber'][0] : null;
                                    $dbUser->position = isset($user[0]['title'][0]) ? $user[0]['title'][0] : null;
                                    $dbUser->department = isset($user[0]['department'][0]) ? $user[0]['department'][0] : null;
                                    $dbUser->role = $_POST['ADUsersImport']['defaultrole'];
                                    $dbUser->company = isset($user[0]['company'][0]) ? $user[0]['company'][0] : null;
                                    $dbUser->umanager = isset($user[0]['manager'][0]) ? $manager_name : null;
                                    $dbUser->room = isset($user[0]['physicaldeliveryofficename'][0]) ? $user[0]['physicaldeliveryofficename'][0] : null;
                                    $dbUser->sendmail = 1;
                                    $dbUser->sendsms = 0;
                                    $dbUser->lang = Yii::app()->params['languages'];
                                    $dbUser->save(false);
                                    $users_create++;
                                } else {
                                    CUsers::model()->updateByPk($userFromDB->id, [
                                        'fullname' => $user[0]['displayname'][0],
                                        'Email' => isset($user[0]['mail'][0]) ? $user[0]['mail'][0] : null,
                                        'Phone' => isset($user[0]['telephonenumber'][0]) ? $user[0]['telephonenumber'][0] : null,
                                        'position' => isset($user[0]['title'][0]) ? $user[0]['title'][0] : null,
                                        'department' => isset($user[0]['department'][0]) ? $user[0]['department'][0] : null,
                                        'umanager' => isset($user[0]['manager'][0]) ? $manager_name : null,
                                        'room' => isset($user[0]['physicaldeliveryofficename'][0]) ? $user[0]['physicaldeliveryofficename'][0] : null,
                                        'role' => $_POST['ADUsersImport']['defaultrole'],
                                        'company' => isset($user[0]['company'][0]) ? $user[0]['company'][0] : null,
                                    ]);
                                    $users_update++;
                                }
                            }
                        }
                    }
                }
            }
            Yii::app()->user->setFlash('info', Yii::t('main-ui',
                '<strong>Успешно импортировано: ' . $users_create . ', Обновлено: ' . $users_update . '.</strong>'));
        }
        $this->render('adusersimport');

    }

    /**
     *
     */
    public function actionADComputersImport()
    {
        if (isset($_POST['ImportGroups']) and !empty($_POST['ImportGroups'])) {
            $users_create = 0;
            $ldap = Yii::app()->ldap;

            foreach ($_POST['ImportGroups'] as $group => $val) {
                $computers = $ldap->folder()->listing(explode('|', $val), adLDAP::ADLDAP_FOLDER, null, 'computer');
                foreach ($computers as $computer) {
                    $username = null;
                    if (!empty($computer['samaccountname'][0])) {
                        $pc_name = trim($computer['samaccountname'][0], "$");
                        $comp_info = $ldap->computer()->info($pc_name,
                            ["cn", "displayname", "dnshostname", "description", "useraccountcontrol"]);
                        if ($comp_info[0]['description'][0] !== null) {
                            $username = CUsers::model()->findByAttributes(['fullname' => $comp_info[0]['description'][0]]);
                        }
                        $exist = Cunits::model()->findByAttributes(['name' => $comp_info[0]['cn'][0]]);
                        if (!$exist and $comp_info[0]['useraccountcontrol'][0] !== '4098' and $comp_info[0]['useraccountcontrol'][0] !== '4130' and $comp_info[0]['useraccountcontrol'][0] !== '528386' and $comp_info[0]['useraccountcontrol'][0] !== '528418') {
                            if ($comp_info[0]['cn'][0]) {
                                $unit = new Cunits;
                                $unit->name = $comp_info[0]['cn'][0];
                                $unit->status = 'Используется';
                                $unit->fullname = $username ? $username->fullname : null;
                                $unit->user = $username ? $username->Username : null;
                                $unit->dept = $username ? $username->department : null;
                                $unit->date = date('dd.mm.YY H:i');
                                $unit->slabel = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e">Используется</span>';
                                $unit->type = $_POST['ADUsersImport']['defaulttype'];
                                $unit->company = $username ? $username->company : $_POST['ADUsersImport']['defaultcompany'];
                                if ($unit->save(false)) {
                                    $users_create++;
                                }
                            }
                        }
                    }
                }
            }
            Yii::app()->user->setFlash('info',
                Yii::t('main-ui', '<strong>Успешно импортировано: ' . $users_create . '</strong>'));
        }
        $this->render('adcomputersimport');

    }

    /**
     *
     */
    public function actionUpdate()
    {
        $current_version = constant('version');
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        $file2 = fopen('https://univef.ru/version.txt', 'r', false, stream_context_create($arrContextOptions));
        if (!$file2) {
            $new_version = Yii::t('main-ui', 'Failed to connect to the update server');
        } else {
            $new_version = fgets($file2, 32);
        }
        fclose($file2);
        $this->render('update', ['current_version' => $current_version, 'new_version' => $new_version]);
    }

    /**
     *
     */
    public function actionUpdateRun()
    {
        set_time_limit(100);
        $file = dirname(__FILE__);
        exec("php $file/../../update.php");
        $current_version = constant('version');
        echo $current_version;
    }

    /**
     * Whatsapp bot settings
     */
    public function actionWhatsapp()
    {
        $file_config = __DIR__ . '/../config/whatsapp.inc';
        $content = file_get_contents($file_config);
        $decode_content = base64_decode($content);
        if ($decode_content) {
            $config_arr = unserialize($decode_content);
        } else {
            $config_arr = [];
        }
        $model = new WhatsappForm();
        $model->setAttributes($config_arr);
        if (isset($_POST['WhatsappForm'])) {
            $config = [
                'enabled' => $_POST['WhatsappForm']['enabled'],
                'token' => $_POST['WhatsappForm']['token'],
                'apiUrl' => $_POST['WhatsappForm']['apiUrl'],
                'webhookUrl' => $_POST['WhatsappForm']['webhookUrl'],
            ];
            $model->setAttributes($config);
            if ($model->validate()) {
                require_once __DIR__ . '../../vendors/whatsapp/chatapi.class.php';
                $api = new ChatApi($model->token, $model->apiUrl);

                $api->setWebhook($_POST['WhatsappForm']['webhookUrl']);

                $string = base64_encode(serialize($config));
                file_put_contents($file_config, $string);
                Yii::app()->user->setFlash('info', Yii::t('main-ui', 'The settings are saved.'));
            }
        }

        $this->render('whatsapp', ['model' => $model]);
    }
}
