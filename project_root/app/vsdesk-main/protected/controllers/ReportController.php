<?php

class ReportController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/design3';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['users', 'users_report', 'exportusers', 'userrelational', 'exportlist'],
                'roles' => ['usersReport'],
            ],
            [
                'allow',
                'actions' => ['customReport', 'exportCustom'],
                'roles' => ['customReport'],
            ],
            [
                'allow',
                'actions' => ['companies', 'companies_report', 'exportcomplist', 'comprelational', 'exportcomps'],
                'roles' => ['companiesReport'],
            ],

            [
                'allow',
                'actions' => [
                    'managerrelational',
                    'managers',
                    'managers_report',
                    'exportmanagers',
                    'exportmanagerlist'
                ],
                'roles' => ['managersReport'],
            ],
            [
                'allow',
                'actions' => [
                    'managerkpirelational',
                    'kpi',
                    'kpireport',
                    'exportmanagerskpi',
                    'exportmanagerlist'
                ],
                'roles' => ['managersKPIReport'],
            ],
            [
                'allow',
                'actions' => [
                    'servicenew',
                    'service',
                    'exportservicelist',
                    'exportservice',
                    'servicerelational',
                    'allFields',
                    'allFieldsReport',
                    'exportallfields'
                ],
                'roles' => ['serviceReport'],
            ],
            [
                'allow',
                'actions' => ['exportassets', 'exportassetlist', 'assetrelational', 'assets'],
                'roles' => ['assetReport'],
            ],
            [
                'allow',
                'actions' => [
                    'unitproblem',
                    'exportproblems',
                    'exportproblemslist',
                    'unitproblemrelational',
                    'exportrequestproblem'
                ],
                'roles' => ['unitProblemReport'],
            ],
            [
                'allow',
                'actions' => [
                    'problems',
                    'serviceproblem',
                    'exportsproblem',
                    'exportsproblemlist',
                    'pservicerelational',
                    'srequests'
                ],
                'roles' => ['monthServiceProblemReport'],
            ],
            [
                'allow',
                'actions' => ['requests', 'requestproblem'],
                'roles' => ['monthServiceRequestsReport'],
            ],
            [
                'allow',
                'actions' => ['srequests', 'requests', 'requestproblem'],
                'roles' => ['requestSReport'],
            ],
            [
                'allow',
                'actions' => [
                    'problems2',
                    'serviceproblems',
                    'exportsproblemlist',
                    'pservicerelational2',
                    'exportsproblem2'
                ],
                'roles' => ['serviceProblemReport'],
            ],
            [
                'allow',
                'actions' => ['unitgroups', 'exportsunits', 'actives', 'exportsactives'],
                'roles' => ['unitSProblemReport'],
            ],

            [
                'deny',// deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'eexcelview' => [
                'class' => 'ext.eexcelview.EExcelBehavior',
            ]
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionIndex()
    {
        $this->render('admin', [//  'model' => $this->loadModel($id),
        ]);
    }

    public function loadModel($id)
    {
        $model = Zreport::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCompanies()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Companies report!</strong> To generate a report, select the period!'));
        $this->render('companies', ['model' => $model]);

    }

    public function actionUsers()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Users report!</strong> To generate a report, select the period!'));
        $this->render('users', ['model' => $model]);

    }

    public function actionProblems()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Psreport('search');
        Yii::app()->user->setFlash('info', Yii::t('main-ui',
            '<strong>Service problems report!</strong> To generate a report, select month and year!'));
        $this->render('problems', ['model' => $model]);

    }

    public function actionRequests()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Psreport('search');
        Yii::app()->user->setFlash('info', Yii::t('main-ui',
            '<strong>Request problems report!</strong> To generate a report, select month and year!'));
        $this->render('requests', ['model' => $model]);

    }

    public function actionProblems2()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Psreport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Service problems report!</strong> To generate a report, select the period!'));
        $this->render('pro', ['model' => $model]);

    }

    public function actionCompanies_report()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
        $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');
        $companies = Companies::model()->findAll();
        $request = [];
        $columns = [];
        $count = null;
        $columns[] = [
            'name' => 'company',
            'header' => Yii::t('main-ui', 'Company'),
            'class' => 'bootstrap.widgets.TbRelationalColumn',
            'url' => $this->createUrl('comprelational',
                ['sdate' => $_POST['Report']['sdate'], 'edate' => $_POST['Report']['edate']]),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $columns[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->label,
            ];
        }

        foreach ($companies as $company) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['id'] = $company->id;
            $request['company'] = $company->name;
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes([
                    'company' => $company->name,
                    'Status' => $status->name
                ], $criteria);
                $request[$status->name] = (int)$count;
            }
            $model[] = $request;
        }

        $this->render('compreport', [
            'model' => $model,
            'columns' => $columns,
        ]);

    }

    public function actionSrequests()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);

        if (isset($_POST['Report']['sdate']) and isset($_POST['Report']['edate'])) {
            $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
            $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');
            $companies = Companies::model()->findAll();
            $count = null;
            $model = null;
            $categories = [];
            foreach ($companies as $company) {
                $criteria = new CDbCriteria;
                $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s',
                        $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '"';
                //$request['id'] = $company->id;
                //$request['name'] = $company->name;
                //$request['name'] = 'Заявки';
                $categories[] = $company->name;
                $count = Request::model()->countByAttributes(['company' => $company->name], $criteria);
                //$request['y'] = $count;
                $request['data'][] = (int)$count;
                $model[] = (int)$count;
            }
            /*echo '<pre>';
            var_dump($model);
            exit;*/
            $this->render('srequests_report', [
                'model' => $model,
                'categories' => $categories,
            ]);
        } else {
            $this->render('srequests', [
                'model' => new Report()
            ]);
        }
    }

    public function actionUsers_report()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
        $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');

        if (isset($_POST['Report']['company']) AND !empty($_POST['Report']['company'])) {
            $criteria_u = new CDbCriteria;
            $criteria_u->condition = 'company = :company';
            $criteria_u->params = [':company' => $_POST['Report']['company']];
            $criteria_u->order = 'fullname ASC';
        } else {
            $criteria_u = new CDbCriteria;
            $criteria_u->order = 'fullname ASC';
        }
        $user_roles = Roles::model()->usersAll($criteria_u);
        $users = [];
        array_walk_recursive($user_roles, function ($value, $key) use (&$users) {
            $users[] = $value;
        });
        $request = [];
        $columns = [];
        $count = null;
        $columns[] = [
            'name' => 'user',
            'header' => Yii::t('main-ui', 'User'),
            'class' => 'bootstrap.widgets.TbRelationalColumn',
            'url' => $this->createUrl('userrelational', [
                'sdate' => $_POST['Report']['sdate'],
                'edate' => $_POST['Report']['edate'],
                'company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null
            ])
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $columns[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->label,
            ];

        }
        foreach ($users as $user) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = $status_all;
            $request['id'] = $user->id;
            $request['user'] = $user->fullname;
            foreach ($statuses as $status) {
                if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                    $count = Request::model()->countByAttributes([
                        'CUsers_id' => $user->Username,
                        'Status' => $status->name,
                        'company' => $_POST['Report']['company']
                    ], $criteria);
                } else {
                    $count = Request::model()->countByAttributes([
                        'CUsers_id' => $user->Username,
                        'Status' => $status->name
                    ], $criteria);
                }
                $request[$status->name] = (int)$count;
            }
            $model[] = $request;
        }
        $this->render('usersreport', [
            'model' => $model,
            'columns' => $columns,
        ]);
    }

    public function actionExportUsers(
        $sdate,
        $edate,
        $company = null
    ) {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($sdate . ' 00:00:00');
        $edate = strtotime($edate . ' 23:59:00');
        $criteria_u = new CDbCriteria;
        if ($company) {
            $criteria_u->condition = 'company = :company';
            $criteria_u->params = [':company' => $company];
            $criteria_u->order = 'fullname ASC';
        } else {
            $criteria_u = new CDbCriteria;
            $criteria_u->order = 'fullname ASC';
        }
        $user_roles = Roles::model()->usersAll($criteria_u);
        $users = [];
        array_walk_recursive($user_roles, function ($value, $key) use (&$users) {
            $users[] = $value;
        });
        $company = $company ? $company . '_' : '';
        $request = [];
        $result = [];
        $count = null;
        $statuss = Status::model()->findAllByAttributes(['enabled' => 1]);
        $column[] = [
            'name' => 'user',
            'header' => Yii::t('main-ui', 'User'),
        ];
        foreach ($statuss as $statusi) {
            $column[] = [
                'name' => $statusi->id,
                'header' => $statusi->name,
            ];
        }

        foreach ($users as $user) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['user'] = $user->fullname;
            $request['sdate'] = date('Y-m-d H:i:s', $sdate);
            $request['edate'] = date('Y-m-d H:i:s', $edate);
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes([
                    'CUsers_id' => $user->Username,
                    'Status' => $status->name
                ], $criteria);
                $request[$status->id] = (int)$count;
            }
            $result[] = $request;
        }
        $config = ['pagination' => false];
        $model = new CArrayDataProvider($result, $config);
        $this->toExcel($model,
            $columns = $column,
            Yii::t('main-ui', 'Users report') . '_' . $company . date('d.m.Y', $sdate) . '-' . date('d.m.Y', $edate),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Users report') . '_' . $company . date('d.m.Y',
                        $sdate) . '-' . date('d.m.Y', $edate),
            ],
            'Excel2007'
        );
    }

    public function actionCompRelational($edate, $sdate)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $id = Yii::app()->getRequest()->getParam('id');
        $company = Companies::model()->findByPk($id);
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        $criteria = new CDbCriteria;
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                $edata) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        $request = Request::model()->findAllByAttributes(['company' => $company->name], $criteria);
        $this->renderPartial('_comprelational', [
            'gridDataProvider' => $request,
            'sdate' => $sdate,
            'edate' => $edate,
            'username' => $company->name,
        ]);
    }

    public function actionUserRelational($edate, $sdate, $company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $id = Yii::app()->getRequest()->getParam('id');
        $username = CUsers::model()->findByPk($id);
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        $criteria = new CDbCriteria;
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                $edata) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        if (!empty($company)) {
            $request = Request::model()->findAllByAttributes([
                'CUsers_id' => $username->Username,
                'company' => $company
            ], $criteria);
        } else {
            $request = Request::model()->findAllByAttributes(['CUsers_id' => $username->Username], $criteria);
        }
        $this->renderPartial('_userrelational', [
            'gridDataProvider' => $request,
            'sdate' => $sdate,
            'edate' => $edate,
            'username' => $username->fullname,
        ]);
    }

    public function actionManagers()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Managers report!</strong> To generate a report, select the period!'));
        $this->render('managers', [
            'model' => $model,
        ]);
    }

    public function actionKpi()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>KPI report!</strong> To generate a report, select the period, type and company!'));
        $this->render('managerskpi', [
            'model' => $model,
        ]);
    }

    public function actionServicenew()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Request by service report!</strong> To generate a report, select the period!'));
        $this->render('servicedates', [
            'model' => $model,
        ]);
    }

    public function actionManagers_report()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
        $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');
        $criteria1 = new CDbCriteria(['order' => 'fullname ASC']);
        $criteria1->select = 'id, fullname, Username';
        $managers = Roles::model()->managersAll($criteria1);

        $criteria = new CDbCriteria;
        $criteria->together = false;
        //$criteria->select = 'id, lead_time';
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                $edate) . '"';

        $connection = Yii::app()->db;

        $criterias = new CDbCriteria;
        $criterias->select = 'name, label';

        $users = [];
        array_walk_recursive($managers, function ($value, $key) use (&$users) {
            $users[] = $value;
        });
        $request = [];
        $columns = [];
        $count = null;
        $columns[] = [
            'name' => 'user',
            'header' => Yii::t('main-ui', 'Manager'),
            'class' => 'bootstrap.widgets.TbRelationalColumn',
            'url' => $this->createUrl('managerrelational', [
                'sdate' => $_POST['Report']['sdate'],
                'edate' => $_POST['Report']['edate'],
                'company' => $_POST['Report']['company'],
                'type' => NULL,
            ]),
        ];
        $columns[] = [
            'name' => 'leadTimeEx',
            'header' => Yii::t('main-ui', 'Lead time'),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1], $criterias);
        $statuses = $status_all;
        foreach ($status_all as $stat_all) {
            $columns[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->label,
            ];
        }

        foreach ($users as $user) {
            $request['id'] = $user->id;
            $request['user'] = $user->fullname;
            $leadTime = [null];

            if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                $reqs = Request::model()->findAllByAttributes([
                    'Managers_id' => $user->Username,
                    'company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null
                ], $criteria);
            } else {
                $reqs = Request::model()->findAllByAttributes([
                    'Managers_id' => $user->Username,
                ], $criteria);
            }
            foreach ($reqs as $req) {
                $leadTime[] = $req->lead_time;
            }

            foreach ($statuses as $status) {
                if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status->name.'\' AND `t`.`company`=\''.$_POST['Report']['company'] .'\' AND (timestamp BETWEEN \''.date('Y-m-d H:i:s', $sdate).'\' AND \''.date('Y-m-d H:i:s', $edate).'\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $count = (int)$count;
                } else {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status->name.'\' AND (timestamp BETWEEN \''.date('Y-m-d H:i:s', $sdate).'\' AND \''.date('Y-m-d H:i:s', $edate).'\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    //var_dump($count);
                    $count = (int)$count;
                }

                $request[$status->name] = $count;
                $query = NULL;
                $count = NULL;
            }
            $request['leadTimeEx'] = $this->sumTime($leadTime);
            $model[] = $request;
        }

        $this->render('managersreport', [
            'model' => $model,
            'columns' => $columns,
        ]);
    }

    public function actionKpiReport()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = $_POST['Report']['sdate'] ? strtotime($_POST['Report']['sdate'] . ' 00:00:00') : strtotime(date('d.m.Y') . '00:00:00');
        $edate = $_POST['Report']['edate'] ? strtotime($_POST['Report']['edate'] . ' 23:59:00'): strtotime(date('d.m.Y') . '23:59:59');

        $users = [];
        $request = [];
        $columns = [];
        $count = null;
        $search = NULL;
        $value = NULL;

        if($_POST['Report']['type'] == 1){
            $criteria = new CDbCriteria(['order' => 'fullname ASC']);
            if(Yii::app()->user->checkAccess('systemManager')){
                $criteria->compare('id', Yii::app()->user->id, false);
            }
            $managers = Roles::model()->managersAll($criteria);
            array_walk_recursive($managers, function ($value, $key) use (&$users) {
                $users[] = $value;
            });
            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Manager'),
                'class' => 'bootstrap.widgets.TbRelationalColumn',
                'url' => $this->createUrl('managerrelational', [
                    'sdate' => $_POST['Report']['sdate'],
                    'edate' => $_POST['Report']['edate'],
                    'company' => $_POST['Report']['company'],
                    'type' => $_POST['Report']['type']
                ]),
            ];
            $columns[] = [
                'name' => 'rating',
                'header' => Yii::t('main-ui', 'Average rating'),
            ];
            $search = 'Managers_id';
        }

        if($_POST['Report']['type'] == 2){
            $criteria = new CDbCriteria(['order' => 'fullname ASC']);
            if(Yii::app()->user->checkAccess('systemUser')){
                $criteria->compare('id', Yii::app()->user->id, false);
            }
            if(isset($_POST['Report']['company']) AND !empty($_POST['Report']['company'])) {
                $criteria->compare('company', $_POST['Report']['company'], false);
            }
            $managers = Roles::model()->usersAll($criteria);
            array_walk_recursive($managers, function ($value, $key) use (&$users) {
                $users[] = $value;
            });
            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'User'),
                'class' => 'bootstrap.widgets.TbRelationalColumn',
                'url' => $this->createUrl('managerrelational', [
                    'sdate' => $_POST['Report']['sdate'],
                    'edate' => $_POST['Report']['edate'],
                    'company' => $_POST['Report']['company'],
                    'type' => $_POST['Report']['type']
                ]),
            ];
            $search = 'CUsers_id';
        }

        if($_POST['Report']['type'] == 3){
            $criteria = new CDbCriteria(['order' => 'name ASC']);
            if(isset($_POST['Report']['company']) AND !empty($_POST['Report']['company'])) {
                $company = Companies::model()->findByAttributes(['name' => $_POST['Report']['company']]);
                $srvs = $company->getServicesArray() + Service::getAllShared();
                foreach ($srvs as $key => $value){
                    $criteria->addSearchCondition('id', $key, false, 'OR', 'LIKE');
                }
                $services = Service::model()->findAll($criteria);
                array_walk_recursive($services, function ($value, $key) use (&$users) {
                    $users[] = $value;
                });

            } else {
                $managers = Service::model()->findAll($criteria);
                array_walk_recursive($managers, function ($value, $key) use (&$users) {
                    $users[] = $value;
                });

            }

            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Service'),
                'class' => 'bootstrap.widgets.TbRelationalColumn',
                'url' => $this->createUrl('managerrelational', [
                    'sdate' => $_POST['Report']['sdate'],
                    'edate' => $_POST['Report']['edate'],
                    'company' => $_POST['Report']['company'],
                    'type' => $_POST['Report']['type']
                ]),
            ];
            $search = 'service_id';
        }

        if($_POST['Report']['type'] == 4){
            $criteria = new CDbCriteria(['order' => 'name ASC']);
            $groups = Groups::model()->findAll($criteria);
            array_walk_recursive($groups, function ($value, $key) use (&$users) {
                $users[] = $value;
            });


            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Group'),
                'class' => 'bootstrap.widgets.TbRelationalColumn',
                'url' => $this->createUrl('managerrelational', [
                    'sdate' => $_POST['Report']['sdate'],
                    'edate' => $_POST['Report']['edate'],
                    'company' => $_POST['Report']['company'],
                    'type' => $_POST['Report']['type']
                ]),
            ];
            $search = 'groups_id';
        }

        $columns[] = [
            'name' => 'opened',
            'header' => Yii::t('main-ui', 'Opened tickets'),
        ];
        $columns[] = [
            'name' => 'inwork',
            'header' => Yii::t('main-ui', 'Tickets was in work'),
        ];
        $columns[] = [
            'name' => 'inwork_success',
            'header' => Yii::t('main-ui', 'Tickets was in work no overdue'),
        ];
        $columns[] = [
            'name' => 'wasclosed',
            'header' => Yii::t('main-ui', 'Tickets was closed'),
        ];
        $columns[] = [
            'name' => 'wasclosed_success',
            'header' => Yii::t('main-ui', 'Tickets was closed no overdue'),
        ];
        $columns[] = [
            'name' => 'reopened',
            'header' => Yii::t('main-ui', 'Reopened tickets'),
        ];
        $columns[] = [
            'name' => 'canceled',
            'header' => Yii::t('main-ui', 'Canceled tickets'),
        ];
        $columns[] = [
            'name' => 'delayed_start',
            'header' => Yii::t('main-ui', 'Ticket was delayed by reaction'),
        ];
        $columns[] = [
            'name' => 'delayed_end',
            'header' => Yii::t('main-ui', 'Ticket was delayed by salvation'),
        ];
        $columns[] = [
            'name' => 'delayed',
            'header' => Yii::t('main-ui', 'Pending tickets'),
        ];
        $columns[] = [
            'name' => 'waspaused',
            'header' => Yii::t('main-ui', 'Tickets was paused'),
        ];
        $columns[] = [
            'name' => 'wasautoclosed',
            'header' => Yii::t('main-ui', 'Tickets was closed automatically'),
        ];
        $columns[] = [
            'name' => 'wasescalated',
            'header' => Yii::t('main-ui', 'Tickets was escalated'),
        ];

        foreach ($users as $user) {
            if($_POST['Report']['type'] == 1){
                $value = $user->Username;
                $user_val = $user->fullname;
            }
            if($_POST['Report']['type'] == 2){
                $value = $user->Username;
                $user_val = $user->fullname;
            }
            if($_POST['Report']['type'] == 3){
                $value = $user->id;
                $user_val = $user->name;
            }
            if($_POST['Report']['type'] == 4){
                $value = $user->id;
                $user_val = $user->name;
            }

            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';

            $criteria_inwork = new CDbCriteria;
            $criteria_inwork->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fStartTime IS NOT NULL';

            $criteria_inwork_success = new CDbCriteria;
            $criteria_inwork_success->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fStartTime IS NOT NULL AND delayed_start <> 1';

            $criteria_wasclosed = new CDbCriteria;
            $criteria_wasclosed->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fEndTime IS NOT NULL';

            $criteria_wasclosed_success = new CDbCriteria;
            $criteria_wasclosed_success->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fEndTime IS NOT NULL AND delayed_end <> 1';

            $request['id'] = $user->id;
            $request['user'] = $user_val;
            //устанавливаем атрибуты для отчета
            if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                $opened = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria);
                $inwork = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria_inwork);
                $inwork_success = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria_inwork_success);
                $wasclosed = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria_wasclosed);
                $wasclosed_success = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria_wasclosed_success);
                $reopened = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'reopened' => 1], $criteria);
                $canceled = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'canceled' => 1], $criteria);
                $delayed_start = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'delayed_start' => 1], $criteria);
                $delayed_end = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'delayed_end' => 1], $criteria);
                $delayed = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'delayed' => 1], $criteria);
                $waspaused = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'waspaused' => 1], $criteria);
                $wasautoclosed = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'wasautoclosed' => 1], $criteria);
                $wasescalated = Request::model()->countByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value, 'wasescalated' => 1], $criteria);
            } else {
                $opened = Request::model()->countByAttributes([$search => $value], $criteria);
                $inwork = Request::model()->countByAttributes([$search => $value], $criteria_inwork);
                $inwork_success = Request::model()->countByAttributes([$search => $value], $criteria_inwork_success);
                $wasclosed = Request::model()->countByAttributes([$search => $value], $criteria_wasclosed);
                $wasclosed_success = Request::model()->countByAttributes([$search => $value], $criteria_wasclosed_success);
                $reopened = Request::model()->countByAttributes([$search => $value, 'reopened' => 1], $criteria);
                $canceled = Request::model()->countByAttributes([$search => $value, 'canceled' => 1], $criteria);
                $delayed_start = Request::model()->countByAttributes([$search => $value, 'delayed_start' => 1], $criteria);
                $delayed_end = Request::model()->countByAttributes([$search => $value, 'delayed_end' => 1], $criteria);
                $delayed = Request::model()->countByAttributes([$search => $value, 'delayed' => 1], $criteria);
                $waspaused = Request::model()->countByAttributes([$search => $value, 'waspaused' => 1], $criteria);
                $wasautoclosed = Request::model()->countByAttributes([$search => $value, 'wasautoclosed' => 1], $criteria);
                $wasescalated = Request::model()->countByAttributes([$search => $value, 'wasescalated' => 1], $criteria);
            }
            if($_POST['Report']['type'] == 1){
                if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                    $criteria_rating = new CDbCriteria;
                    $criteria_rating->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '"  AND rating IS NOT NULL';
                    $rating_arr = Request::model()->findAllByAttributes(['company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null, $search => $value], $criteria_rating);
                } else {
                    $criteria_rating = new CDbCriteria;
                    $criteria_rating->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND rating IS NOT NULL';
                    $rating_arr = Request::model()->findAllByAttributes([$search => $value], $criteria_rating);
                }
                if(!empty($rating_arr)){
                    $rating_count = (int)count($rating_arr);
                }
                $i = (int)0;
                foreach ($rating_arr as $rating_item){
                    $i = (int)$i+(int)$rating_item->rating;
                }
                $rating = (int)$i/(int)$rating_count;
            }

            $request['opened'] = (int)$opened;
            $request['inwork'] = (int)$inwork !==0 ? (int)$inwork .' / '. round(($inwork/$opened)*100) . '%' : '0 / 0%';
            $request['inwork_success'] = (int)$inwork_success !==0 ?(int)$inwork_success.' / '. round(($inwork_success/$opened)*100) . '%' : '0 / 0%';
            $request['wasclosed'] = (int)$wasclosed !==0 ? (int)$wasclosed.' / '. round(($wasclosed/$opened)*100) . '%' : '0 / 0%';
            $request['wasclosed_success'] = (int)$wasclosed_success !==0 ?(int)$wasclosed_success.' / '. round(($wasclosed_success/$opened)*100) . '%' : '0 / 0%';
            $request['reopened'] = (int)$reopened !==0 ? (int)$reopened.' / '. round(($reopened/$opened)*100) . '%' : '0 / 0%';
            $request['canceled'] = (int)$canceled !==0 ? (int)$canceled.' / '. round(($canceled/$opened)*100) . '%' : '0 / 0%';
            $request['delayed_start'] = (int)$delayed_start !== 0 ? (int)$delayed_start.' / '. round(($delayed_start/$opened)*100) . '%' : '0 / 0%';
            $request['delayed_end'] = (int)$delayed_end !==0 ? (int)$delayed_end.' / '. round(($delayed_end/$opened)*100) . '%' : '0 / 0%';
            $request['delayed'] = (int)$delayed !==0 ? (int)$delayed.' / '. round(($delayed/$opened)*100) . '%' : '0 / 0%';
            $request['waspaused'] = (int)$waspaused !== 0 ? (int)$waspaused.' / '. round(($waspaused/$opened)*100) . '%' : '0 / 0%';
            $request['wasautoclosed'] = (int)$wasautoclosed !==0 ? (int)$wasautoclosed.' / '. round(($wasautoclosed/$opened)*100) . '%' : '0 / 0%';
            $request['wasescalated'] = (int)$wasescalated !==0 ? (int)$wasescalated.' / '. round(($wasescalated/$opened)*100) . '%' : '0 / 0%';
            $request['rating'] = (int)$rating !==0 ? (int)$rating : NULL;
            $model[] = $request;
        }
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('managerskpireport', [
            'model' => $model,
            'columns' => $columns,
            'sdate' => date('d.m.Y', $sdate),
            'edate' => date('d.m.Y', $edate),
            'type' => $_POST['Report']['type']
        ]);
    }

    function sumTime($arr)
    {
        $temp_value = null;
        $hours = 0;
        $seconds = 0;
        $minutes = 0;
        foreach ($arr as $value) {
            $temp_value = explode(':', $value);

            $hours += $temp_value[0];
            $minutes += $temp_value[1];
            $seconds += $temp_value[2];
        }
        while ($seconds >= 60) {
            $minutes++;
            $seconds -= 60;
        }
        while ($minutes >= 60) {
            $hours++;
            $minutes -= 60;
        }
        $res_time = str_pad($hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minutes, 2, 0,
                STR_PAD_LEFT) . ':' . str_pad($seconds, 2, 0, STR_PAD_LEFT);
        return $res_time;
    }

    public function actionManagerRelational($edate, $sdate, $company = null, $type = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $id = Yii::app()->getRequest()->getParam('id');
        if(null == $type){
            $stt = array();
            $username = CUsers::model()->findByPk($id);
            $name = $username->id;
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $criteria = new CDbCriteria;
            foreach ($statuses as $status) {
                $stt[$status->name] = $status->name;
            }
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            $criteria->addInCondition('Status', $stt, 'AND');
            if (!empty($company)) {
                $request = Request::model()->findAllByAttributes([
                    'Managers_id' => $username->Username,
                    'company' => $company
                ], $criteria);
            } else {
                $request = Request::model()->findAllByAttributes(['Managers_id' => $username->Username], $criteria);
            }
        }
        if(isset($type) AND $type == 1){
            $criteria = new CDbCriteria;
            $username = CUsers::model()->findByPk($id);
            $name = $username->id;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $request = Request::model()->findAllByAttributes([
                    'Managers_id' => $username->Username,
                    'company' => $company
                ], $criteria);
            } else {
                $request = Request::model()->findAllByAttributes(['Managers_id' => $username->Username], $criteria);
            }
        }

        if(isset($type) AND $type == 2){
            $criteria = new CDbCriteria;
            $username = CUsers::model()->findByPk($id);
            $name = $username->id;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $request = Request::model()->findAllByAttributes([
                    'CUsers_id' => $username->Username,
                    'company' => $company
                ], $criteria);
            } else {
                $request = Request::model()->findAllByAttributes(['CUsers_id' => $username->Username], $criteria);
            }
        }
        if(isset($type) AND $type == 3){
            $criteria = new CDbCriteria;
            $username = Service::model()->findByPk($id);
            $name = $username->id;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $request = Request::model()->findAllByAttributes([
                    'service_id' => $id,
                    'company' => $company
                ], $criteria);
            } else {
                $request = Request::model()->findAllByAttributes(['service_id' => $id], $criteria);
            }
        }
        if(isset($type) AND $type == 4){
            $criteria = new CDbCriteria;
            $username = Groups::model()->findByPk($id);
            $name = $username->id;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $request = Request::model()->findAllByAttributes([
                    'groups_id' => $id,
                    'company' => $company
                ], $criteria);
            } else {
                $request = Request::model()->findAllByAttributes(['groups_id' => $id], $criteria);
            }
        }

        $this->renderPartial('_managerrelational', [
            'gridDataProvider' => $request,
            'sdate' => $sdate,
            'edate' => $edate,
            'company' => $company,
            'username' => $name,
            'type' => $type,
        ]);
    }

    public function actionAssets()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);

        if (Yii::app()->user->checkAccess('systemUser')) {
            $user = CUsers::model()->findByPk(Yii::app()->user->id);
            $assets = Cunits::model()->findAllByAttributes(['company' => $user->company]);
        } else {
            if (isset($_POST['company']) and !empty($_POST['company'])) {
                $assets = Cunits::model()->findAllByAttributes(['company' => $_POST['company']]);
            } else {
                $assets = Cunits::model()->findAll();
            }
        }

        $request = [];
        $columns = [];
        $count = null;
        $columns[] = [
            'name' => 'asset',
            'header' => Yii::t('main-ui', 'Asset'),
            'class' => 'bootstrap.widgets.TbRelationalColumn',
            //'url' => $this->createUrl('assetrelational', array('sdate' => $sdate, 'edate' => $edate)),
            'url' => $this->createUrl('assetrelational'),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $columns[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->label,
            ];
        }

        foreach ($assets as $asset) {
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['id'] = $asset->id;
            $request['asset'] = $asset->name;
            $criteria = new CDbCriteria;
            $criteria->compare('cunits', $asset->name, true);
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes(['Status' => $status->name], $criteria);
                $request[$status->name] = (int)$count;
            }
            $model[] = $request;
        }
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('assets', [
            'model' => $model,
            'columns' => $columns,
        ]);
    }

    public function actionUnitGroups()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Sureport::model()->deleteAll();
        $locations = Cunits::model()->getDistinctLocations();
        foreach ($locations as $location) {
            $types = CunitTypes::model()->findAll();
            foreach ($types as $avalue) {
                $units = Cunits::model()->countAndCostByAttributes([
                    'location' => $location,
                    'type' => $avalue->name
                ]);
                $summary = 0;
                foreach ($units as $unit) {
                    $summary = $summary + $unit->cost;
                }
                $model = new Sureport;
                $model->dept = $location;
                $model->type = $avalue->name;
                $model->count = $units[0]['count'];
                $model->summary = $units[0]['cost'];
                $model->save(false);
            }
        }
        $model = new Sureport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('sunits', [
            'model' => $model,
        ]);
    }
    public function actionActives()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Sactivesreport::model()->deleteAll();
        $locations = Asset::model()->getDistinctLocations();

        foreach ($locations as $location) {
            $types = CactivesTypes::model()->findAll();
            foreach ($types as $avalue) {
                $units = Asset::model()->countAndCostByAttributes([
                    'location' => $location,
                    'asset_attrib_name' => $avalue->name
                ]);
                
                // var_dump($location);
                // $summary = 0;
                // foreach ($units as $unit) {
                //     $summary = $summary + $unit->cost;
                //     echo '<pre>';
                // var_dump($units);
                // echo '</pre>';
                // }
                $model = new Sactivesreport;
                $model->dept = $location;
                $model->type = $avalue->name;
                $model->count = $units[0]['count'];
                $model->summary = $units[0]['cost'];
                $model->save(false);
            }
        }
        $model = new Sactivesreport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('sactives', [
            'model' => $model,
        ]);
    }

    public function actionAssetRelational()
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $asset = Cunits::model()->findByPk($id);
        $criteria = new CDbCriteria;
        $criteria->compare('cunits', $asset->name, true);
        $request = Request::model()->findAll($criteria);
        $this->renderPartial('_assetrelational', [
            'gridDataProvider' => $request,
            'asset' => $asset->name,
        ]);
    }

    public function actionService()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
        $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');
        $shared = Service::getAllShared();
        if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
            $allServices = $shared;
            $company = Companies::model()->findByAttributes(['name' => $_POST['Report']['company']]);
            if ($company) {
                $companyServices = $company->getServicesArray();
                foreach ($companyServices as $key => $value) {
                    if (!isset($allServices[$key])) {
                        $allServices[$key] = $value;
                    }
                }
            }
            /** @var Depart $depart */
            $depart = Depart::model()->findAllByAttributes(['company' => $_POST['Report']['company']]);
            if ($depart) {
                foreach ($depart as $item) {
                    $dep = Depart::model()->findByPk($item->id);
                    $departServices = $dep->getServicesArray();
                    foreach ($departServices as $key => $value) {
                        if (!isset($allServices[$key])) {
                            $allServices[$key] = $value;
                        }
                    }
                }

            }
            if (isset($allServices) AND !empty($allServices)) {
                foreach ($allServices as $service => $value) {
                    $services[] = Service::model()->findByPk($service);
                }
            } else {
                $services = $shared;
            }

        } else {
            $services = Service::model()->findAll();
        }

        $request = [];
        $columns = [];
        $count = null;
        $columns[] = [
            'name' => 'service',
            'header' => Yii::t('main-ui', 'Service'),
            'class' => 'bootstrap.widgets.TbRelationalColumn',
            'url' => $this->createUrl('servicerelational', [
                'sdate' => $sdate,
                'edate' => $edate,
                'company' => $_POST['Report']['company'] ? $_POST['Report']['company'] : null
            ]),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $columns[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->label,
            ];
        }

        foreach ($services as $service) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['id'] = $service->id;
            $request['service'] = $service->name;
            foreach ($statuses as $status) {
                if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
                    $count = Request::model()->countByAttributes([
                        'service_id' => $service->id,
                        'Status' => $status->name,
                        'company' => $_POST['Report']['company']
                    ], $criteria);
                } else {
                    $count = Request::model()->countByAttributes([
                        'service_id' => $service->id,
                        'Status' => $status->name
                    ], $criteria);
                }
                $request[$status->name] = (int)$count;
            }
            $model[] = $request;
        }
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('service', [
            'model' => $model,
            'columns' => $columns,
            'sdate' => $sdate,
            'edate' => $edate,
        ]);
    }

    public function actionServiceRelational($sdate, $edate, $company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $id = Yii::app()->getRequest()->getParam('id');
        $service = Service::model()->findByPk($id);
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        $criteria = new CDbCriteria;
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                $edate) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        if (!empty($company)) {
            $request = Request::model()->findAllByAttributes(['service_id' => $id, 'company' => $company],
                $criteria);
        } else {
            $request = Request::model()->findAllByAttributes(['service_id' => $id], $criteria);
        }
        $this->renderPartial('_servicerelational', [
            'gridDataProvider' => $request,
            'service' => $service->name,
            'sdate' => date('Y-m-d', $sdate),
            'edate' => date('Y-m-d', $edate),
            'company' => $company,
        ]);
    }

    public function actionPServiceRelational($year, $month)
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $zid = Psreport::model()->findByPk($id);
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . $year . '-' . $month . '-01 00:00:00' . '" AND "' . $year . '-' . $month . '-31 23:59:59' . '"';
        $service = Service::model()->findByAttributes(['name' => $zid->servicename]);
        $request = Problems::model()->findAllByAttributes(['service' => $service->name], $criteria);
        $this->renderPartial('_pservicerelational', [
            'gridDataProvider' => $request,
            'service' => $service->name,
        ]);
    }

    public function actionPServiceRelational2($sdate, $edate)
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $zid = Psreport::model()->findByPk($id);
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                $edate) . '"';
        $service = Service::model()->findByAttributes(['name' => $zid->servicename]);
        $request = Problems::model()->findAllByAttributes(['service' => $service->name], $criteria);
        $this->renderPartial('_pservicerelational', [
            'gridDataProvider' => $request,
            'service' => $service->name,
        ]);
    }

    public function actionUnitProblem()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Pureport::model()->deleteAll();

        if (Yii::app()->user->checkAccess('systemUser')) {
            $user = CUsers::model()->findByPk(Yii::app()->user->id);
            $assets = Cunits::model()->findAllByAttributes(['company' => $user->company]);
        } else {
            if (isset($_POST['company']) and !empty($_POST['company'])) {
                $assets = Cunits::model()->findAllByAttributes(['company' => $_POST['company']]);
            } else {
                $assets = Cunits::model()->findAll();
            }
        }

        foreach ($assets as $asset) {

            $model = new Pureport;
            $criteria = new CDbCriteria;
            $criteria->compare('assets_names', $asset->name, true);
            $request = Problems::model()->countByAttributes(['status' => 'Зарегистрирована'], $criteria);
            $request2 = Problems::model()->countByAttributes(['status' => 'Обходное решение'], $criteria);
            $request3 = Problems::model()->countByAttributes(['status' => 'Решена'], $criteria);

            $stnew = (int)$request;
            $stworkaround = (int)$request2;
            $stsolved = (int)$request3;

            $model->assetname = $asset->name;
            $model->assettype = $asset->type;
            $model->status = $asset->status;
            $model->slabel = $asset->slabel;
            $model->date = date("d.m.Y H:i");
            $model->stnew = $stnew;
            $model->stworkaround = $stworkaround;
            $model->stsolved = $stsolved;
            $model->save(false);

        }
        $model = new Pureport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('units', [
            'model' => $model,
        ]);
    }

    public function actionServiceProblem()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Psreport::model()->deleteAll();
        $services = Service::model()->findAll();
        $year = $_POST['Psreport']['year'];
        $month = $_POST['Psreport']['date'];
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . $year . '-' . $month . '-01 00:00:00' . '" AND "' . $year . '-' . $month . '-31 23:59:59' . '"';
        foreach ($services as $service) {

            $model = new Psreport;
            $request = Problems::model()->countByAttributes([
                'status' => 'Зарегистрирована',
                'service' => $service->name
            ], $criteria);
            $request2 = Problems::model()->countByAttributes([
                'status' => 'Обходное решение',
                'service' => $service->name
            ], $criteria);
            $request3 = Problems::model()->countByAttributes(['status' => 'Решена', 'service' => $service->name],
                $criteria);
            $request4 = Problems::model()->findAllByAttributes(['service' => $service->name], $criteria);

            $stnew = (int)$request;
            $stworkaround = (int)$request2;
            $stsolved = (int)$request3;
            $hour = '00';
            $min = '00';
            foreach ($request4 as $item) {
                list($h, $m) = explode(':', $item->downtime);
                $hour = $hour + $h;
                $min = $min + $m;
            }
            $paval = $service->availability;
            $dwh = strtotime(" + $hour hours $min minutes", strtotime('00:00'));// суммируем часы и минуты простоя.
            $rtime = date('H:i', $dwh);
            list($hh, $mm) = explode(':', $rtime);
            if ($mm !== '00') {
                $minper = 0.0166666666666667 * $mm; //переводим минуты в десятичные дроби часа
                $minn = $minper;
            } else {
                $minn = 0;
            }
            $full = $hh + $minn;//количество часов с минутами в десятичных дробях.
            $days = date('t') * 24 / 100; //количество часов в 1% месячной нормы.
            $rrtime = 100 - (round($full / $days,
                    2)); //получаем доступность в % отняв от 100% кол-во часов простоя деленное на кол-во часов в 1%.
            $model->servicename = $service->name;
            $model->date = date("d.m.Y H:i");
            $model->stnew = $stnew;
            $model->stworkaround = $stworkaround;
            $model->stsolved = $stsolved;
            $model->downtime = $rtime;
            $model->availability = $rrtime;
            $model->pavailability = $paval;
            $model->save(false);

        }
        $model = new Psreport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report for ') . $month . '-' . $year . Yii::t('main-ui',
                ' created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('service_problems', [
            'model' => $model,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function actionRequestProblem()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);

        $report = [];
        $criteria_u = new CDbCriteria;
        if (Yii::app()->user->checkAccess('systemUser')) {
            $user = CUsers::model()->findByPk(Yii::app()->user->id);
            $criteria_u->condition = 'company_name = :company';
            $criteria_u->params = [':company' => $user->company];
        }
        $services = Service::model()->findAll($criteria_u);
        $year = $_POST['Psreport']['year'];
        $month = $_POST['Psreport']['date'];
        $status = Status::model()->findByAttributes(['close' => 3]);
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . $year . '-' . $month . '-01 00:00:00' . '" AND "' . $year . '-' . $month . '-31 23:59:59' . '"';
        foreach ($services as $service) {

            $model = [];
            $model['id'] = $service->id;
            $model['parent_service_id'] = $service->parent_service_id;
            $model['parent_service'] = $service->pservice_rl->name;
            $model['parent_pavailability'] = $service->pservice_rl->availability;

            $requests = Request::model()->findAllByAttributes([
                'service_id' => $service->id,
                'Status' => $status->name
            ], $criteria);
            $rcount = Request::model()->countByAttributes([
                'service_id' => $service->id,
                'Status' => $status->name
            ], $criteria);
            $requests_count = (int)$rcount;

            $hour = '00';
            $min = '00';
            $sec = '00';

            $availability_full = 0;

            foreach ($requests as $item) {
                if (!empty($item->lead_time)) {
                    list($h, $m, $s) = explode(':', $item->lead_time);
                    $hour = (int)$hour + (int)$h;
                    $min = (int)$min + (int)$m;
                    $sec = (int)$sec + (int)$s;

                    $pm = $min % 60;
                    $ph = (int)($min / 60);

                    $hh = (int)$hour + (int)$ph;
                    $mm = $pm;

                    if ($mm !== '00') {
                        $minper = 0.0166666666666667 * $mm; //переводим минуты в десятичные дроби часа
                        $minn = $minper;
                    } else {
                        $minn = 0;
                    }

                    $full = $hh + $minn; //количество часов с минутами в десятичных дробях.
                    $availability_full += ((720 - $full) / 720) * 100;
                }
            }

            $service_availability = $service->availability;

            $model['servicename'] = $service->name;
            $model['stnew'] = $requests_count;
            //$model['downtime'] = (string)($hh.':'.$mm);
            //*$model->availability = $rrtime;
            //$availability = ((720 - $full) / 720) * 100;
            if ($requests_count != 0) {
                $availability = sprintf("%01.2f", ($availability_full / $requests_count));
            } else {
                $availability = 100;
            }
            $model['availability'] = $availability;
            //*$model->pavailability = $paval;
            $model['pavailability'] = $service_availability;

            $report[] = $model;
            //echo '<pre>'; var_dump($model); exit;
            //*$model->save(false);

        }

        $total_ar = [];
        foreach ($report as $row) {
            if (!isset($total_ar[$row['parent_service_id']]['availability'])) {
                $total_ar[$row['parent_service_id']]['availability'] = $row['availability'];
                $total_ar[$row['parent_service_id']]['count'] = 1;
            } else {
                $total_ar[$row['parent_service_id']]['availability'] += $row['availability'];
                $total_ar[$row['parent_service_id']]['count'] += 1;
            }

            if (!isset($total_ar[$row['parent_service_id']]['stnew'])) {
                $total_ar[$row['parent_service_id']]['stnew'] = $row['stnew'];
            } else {
                $total_ar[$row['parent_service_id']]['stnew'] += $row['stnew'];
            }
        }

        foreach ($total_ar as $key => $total) {
            if ($total['count'] == 0) {
                $total_ar[$key]['availability'] = 100;
            } else {
                $total_ar[$key]['availability'] = $total['availability'] / $total['count'];
            }
        }

        for ($i = 0; $i < count($report); $i++) {
            $report[$i]['parent_availability'] = sprintf("%01.2f",
                ($total_ar[$report[$i]['parent_service_id']]['availability']));
        }

        //*echo '<pre>'; var_dump($report);
        //*var_dump($total_ar); exit;

        //*$model = new Psreport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report for ') . $month . '-' . $year . Yii::t('main-ui',
                ' created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('request_problems', [
            'model' => $report,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function actionServiceProblems()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Psreport::model()->deleteAll();
        if (isset($_POST['Psreport']['company']) and !empty($_POST['Psreport']['company'])) {
            $allServices = array();
            $company = Companies::model()->findByAttributes(['name' => $_POST['Psreport']['company']]);
            if ($company) {
                $companyServices = $company->getServicesArray();
                foreach ($companyServices as $key => $value) {
                    if (!isset($allServices[$key])) {
                        $allServices[$key] = $value;
                    }
                }
            }
            /** @var Depart $depart */
            $depart = Depart::model()->findAllByAttributes(['company' => $_POST['Psreport']['company']]);
            if ($depart) {
                foreach ($depart as $item) {
                    $dep = Depart::model()->findByPk($item->id);
                    $departServices = $dep->getServicesArray();
                    foreach ($departServices as $key => $value) {
                        if (!isset($allServices[$key])) {
                            $allServices[$key] = $value;
                        }
                    }
                }

            }
            foreach ($allServices as $service => $value) {
                $services[] = Service::model()->findByPk($service);
            }
        } else {
            $services = Service::model()->findAll();
        }
        $sdate = strtotime($_POST['Psreport']['sdate'] . ' 00:00:00');
        $edate = strtotime($_POST['Psreport']['edate'] . ' 23:59:00');
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                $edate) . '"';
        foreach ($services as $service) {

            $model = new Psreport;
            $request = Problems::model()->countByAttributes([
                'status' => 'Зарегистрирована',
                'service' => $service->name
            ], $criteria);
            $request2 = Problems::model()->countByAttributes([
                'status' => 'Обходное решение',
                'service' => $service->name
            ], $criteria);
            $request3 = Problems::model()->countByAttributes(['status' => 'Решена', 'service' => $service->name],
                $criteria);
            //$request4 = Problems::model()->findAllByAttributes(array('service' => $service->name), $criteria);

            $stnew = (int)$request;
            $stworkaround = (int)$request2;
            $stsolved = (int)$request3;
            $model->servicename = $service->name;
            $model->date = date("d.m.Y H:i");
            $model->stnew = $stnew;
            $model->stworkaround = $stworkaround;
            $model->stsolved = $stsolved;
            $model->save(false);

        }
        $model = new Psreport('search');
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Report for ') . date('d-m-Y', $sdate) . ' - ' . date('d-m-Y',
                $edate) . Yii::t('main-ui', ' created!</strong> You can export this report to Microsoft Excel.'));
        $this->render('service_problems2', [
            'model' => $model,
            'sdate' => $sdate,
            'edate' => $edate,
        ]);
    }

    public function actionUnitProblemRelational()
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $zid = Pureport::model()->findByPk($id);
        $asset = Cunits::model()->findByAttributes(['name' => $zid->assetname]);
        $criteria = new CDbCriteria;
        $criteria->compare('assets_names', $asset->name, true);
        $request = Problems::model()->findAll($criteria);
        $this->renderPartial('_unitrelational', [
            'gridDataProvider' => $request,
            'asset' => $asset->name,
        ]);
    }

    public function actionExportComps($sdate, $edate)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($sdate . ' 00:00:00');
        $edate = strtotime($edate . ' 23:59:00');
        $companies = Companies::model()->findAll();
        $request = [];
        $result = [];
        $count = null;
        $statuss = Status::model()->findAllByAttributes(['enabled' => 1]);
        $column[] = [
            'name' => 'company',
            'header' => Yii::t('main-ui', 'Company'),
        ];
        foreach ($statuss as $statusi) {
            $column[] = [
                'name' => $statusi->id,
                'header' => $statusi->name,
            ];
        }

        foreach ($companies as $company) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['company'] = $company->name;
            $request['sdate'] = date('Y-m-d H:i:s', $sdate);
            $request['edate'] = date('Y-m-d H:i:s', $edate);
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes([
                    'company' => $company->name,
                    'Status' => $status->name
                ], $criteria);
                $request[$status->id] = (int)$count;
            }
            $result[] = $request;
        }
        $config = array('pagination' => false);
        $model = new CArrayDataProvider($result, $config);
        $this->toExcel($model,
            $columns = $column,
            Yii::t('main-ui', 'Companies report') . '_' . date('d.m.Y', $sdate) . '-' . date('d.m.Y', $edate),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Companies report') . '_' . date('d.m.Y', $sdate) . '-' . date('d.m.Y',
                        $edate),
            ],
            'Excel2007'
        );
    }

    public function actionExportSunits()
    {
        $model = Sureport::model()->findAll();
        $this->toExcel($model,
            $columns = [
                'dept:text:'.Yii::t('main-ui', 'Location'),
                'type',
                'count',
                // 'summary',
            ],

            'Суммарный отчет по КЕ',
            [
                'creator' => 'UNIVEF services desk',
                'title' => 'Суммарный отчет по КЕ',
            ],
            'Excel2007'
        );
    }

    public function actionExportSactives()
    {
        $model = Sactivesreport::model()->findAll();
        $this->toExcel($model,
            $columns = [
                'dept:text:'.Yii::t('main-ui', 'Location'),
                'type',
                'count',
                'summary',
            ],

            'Суммарный отчет по активам',
            [
                'creator' => 'UNIVEF services desk',
                'title' => 'Суммарный отчет по активам',
            ],
            'Excel2007'
        );
    }

    public function actionExportProblemsList($asset)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('assets_names', $asset, true);
        $model = Problems::model()->findAll($criteria);
        $this->toExcel($model,
            $columns = [
                'id',
                'date',
                'status',
                'priority',
                'influence',
                'downtime',
                'service',
                'manager',
                'assets_names',
                'users',
                'description',
                'workaround:html',
                'decision:html',
            ],

            'Отчет по проблемам_' . $asset,
            [
                'creator' => 'Univef',
                'title' => 'Отчет по проблемам_' . $asset,
            ],
            'Excel2007'
        );
    }

    public function actionExportSProblemList($service)
    {
        $model = Problems::model()->findAllByAttributes(['service' => $service]);
        $this->toExcel($model,
            $columns = [
                'id',
                'date',
                'status',
                'priority',
                'influence',
                'downtime',
                'service',
                'manager',
                'assets_names',
                'users',
                'description',
                'workaround:html',
                'decision:html',
            ],

            'Отчет по проблемам_' . $service,
            [
                'creator' => 'Univef',
                'title' => 'Отчет по проблемам_' . $service,
            ],
            'Excel2007'
        );
    }

    public function actionExportCompList($sdate, $edate, $username)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        $criteria = new CDbCriteria;
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                $edata) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        $model = Request::model()->findAllByAttributes(['company' => $username], $criteria);
        $this->toExcel($model,
            $columns = [
                'id',
                'Name',
                'Date',
                'lead_time',
                'Status:html',
                'ZayavCategory_id',
                'StartTime',
                'fStartTime',
                'EndTime',
                'fEndTime',
                'service_name',
                'Priority',
                //'mfullname',
                'fullname',
                'Address',
                'company',
                'cunits',
                'Content:html'
            ],

            'Отчет по заявкам_' . $username . '_' . $sdate . '-' . $edate,
            [
                'creator' => 'Univef',
                'title' => 'Отчет по заявкам_' . $username . '_' . $sdate . '-' . $edate,
            ],
            'Excel2007'
        );
    }

    public function actionExportList($sdate, $edate, $username)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        $criteria = new CDbCriteria;
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                $edata) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        $model = Request::model()->findAllByAttributes(['fullname' => $username], $criteria);
        $this->toExcel($model,
            $columns = [
                'id',
                'Name',
                'Date',
                'lead_time',
                'Status:html',
                'ZayavCategory_id',
                'StartTime',
                'fStartTime',
                'EndTime',
                'fEndTime',
                'service_name',
                'Priority',
                //'mfullname',
                'fullname',
                'Address',
                'company',
                'cunits',
                'Content:html'
            ],

            'Отчет по заявкам_' . $username . '_' . $sdate . '-' . $edate,
            [
                'creator' => 'Univef',
                'title' => 'Отчет по заявкам_' . $username . '_' . $sdate . '-' . $edate,
            ],
            'Excel2007'
        );
    }

    public function actionExportManagerList($sdate, $edate, $username, $company = null, $type = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $companyn = $company ? $company . '_' : 'Все компании_';
        if(null == $type){
            $criteria = new CDbCriteria;
            $username = CUsers::model()->findByPk($username);
            $name = $username->fullname;
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            foreach ($statuses as $status) {
                $stt[$status->name] = $status->name;
            }
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            $criteria->addInCondition('Status', $stt, 'AND');
            if (!empty($company)) {
                $model = Request::model()->findAllByAttributes(['Managers_id' => $username->Username, 'company' => $company],
                    $criteria);
            } else {
                $model = Request::model()->findAllByAttributes(['Managers_id' => $username->Username], $criteria);
            }
        }
        if(isset($type) AND $type == 1){
            $criteria = new CDbCriteria;
            $username = CUsers::model()->findByPk($username);
            $name = $username->fullname;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $model = Request::model()->findAllByAttributes([
                    'Managers_id' => $username->Username,
                    'company' => $company
                ], $criteria);
            } else {
                $model = Request::model()->findAllByAttributes(['Managers_id' => $username->Username], $criteria);
            }
        }

        if(isset($type) AND $type == 2){
            $criteria = new CDbCriteria;
            $username = CUsers::model()->findByPk($username);
            $name = $username->fullname;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $model = Request::model()->findAllByAttributes([
                    'CUsers_id' => $username->Username,
                    'company' => $company
                ], $criteria);
            } else {
                $model = Request::model()->findAllByAttributes(['CUsers_id' => $username->Username], $criteria);
            }
        }
        if(isset($type) AND $type == 3){
            $criteria = new CDbCriteria;
            $username = Service::model()->findByPk($username);
            $name = $username->name;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $model = Request::model()->findAllByAttributes([
                    'service_id' => $username->id,
                    'company' => $company
                ], $criteria);
            } else {
                $model = Request::model()->findAllByAttributes(['service_id' => $username->id], $criteria);
            }
        }
        if(isset($type) AND $type == 4){
            $criteria = new CDbCriteria;
            $username = Groups::model()->findByPk($username);
            $name = $username->name;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                    $edata) . '"';
            if (!empty($company)) {
                $model = Request::model()->findAllByAttributes([
                    'groups_id' => $username->id,
                    'company' => $company
                ], $criteria);
            } else {
                $model = Request::model()->findAllByAttributes(['groups_id' => $username->id], $criteria);
            }
        }


        $this->toExcel($model,
            $columns = [
                'id',
                'Name',
                'Date',
                'lead_time',
                'rating',
                'Status:html',
                'ZayavCategory_id',
                'StartTime',
                'fStartTime',
                'EndTime',
                'fEndTime',
                'service_name',
                'Priority',
                //'mfullname',
                'fullname',
                'Address',
                'company',
                'cunits',
                'Content:html'
            ],

            Yii::t('main-ui', 'KPI report'). '_' . $companyn . $name . '_' . $sdate . '-' . $edate,
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'KPI report'). '_' . $companyn . $name . '_' . $sdate . '-' . $edate,
            ],
            'Excel2007'
        );
    }

    public function actionExportServiceList($sdate, $edate, $service, $company = null)
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdata = strtotime($sdate . ' 00:00:00');
        $edata = strtotime($edate . ' 23:59:00');
        $companyn = $company ? $company . '_' : 'Все компании_';
        $criteria = new CDbCriteria;
        $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($statuses as $status) {
            $stt[$status->name] = $status->name;
        }
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdata) . '" AND "' . date('Y-m-d H:i:s',
                $edata) . '"';
        $criteria->addInCondition('Status', $stt, 'AND');
        if (!empty($company)) {
            $model = Request::model()->findAllByAttributes(['service_name' => $service, 'company' => $company],
                $criteria);
        } else {
            $model = Request::model()->findAllByAttributes(['service_name' => $service], $criteria);
        }

        $this->toExcel($model,
            $columns = [
                'id',
                'Name',
                'Date',
                'lead_time',
                'Status:html',
                'ZayavCategory_id',
                'StartTime',
                'fStartTime',
                'EndTime',
                'fEndTime',
                'service_name',
                'Priority',
                'mfullname',
                'fullname',
                'Address',
                'company',
                'cunits',
                'Content:html'
            ],

            $service . '_' . $companyn . '_' . $sdate . '-' . $edate,
            [
                'creator' => 'Univef',
                'title' => $service . '_' . $companyn . '_' . $sdate . '-' . $edate,
            ],
            'Excel2007'
        );
    }

    function actionExportAssetList($asset)
    {
        $model = Request::model()->findAllByAttributes(['cunits' => $asset]);
        $this->toExcel($model,
            $columns = [
                'id',
                'Name',
                'Date',
                'lead_time',
                'Status:html',
                'ZayavCategory_id',
                'StartTime',
                'fStartTime',
                'EndTime',
                'fEndTime',
                'service_name',
                'Priority',
                'mfullname',
                'fullname',
                'Address',
                'company',
                'cunits',
                'Comment',
                'Content:html'
            ],

            $asset,
            [
                'creator' => 'Univef',
                'title' => $asset,
            ],
            'Excel2007'
        );
    }

    public function actionExportManagers($sdate, $edate, $company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = strtotime($sdate . ' 00:00:00');
        $edate = strtotime($edate . ' 23:59:00');
        $company_name = $company ? $company . '_' : 'Все компании_';
        $criteria = new CDbCriteria(['order' => 'fullname ASC']);
        $managers = Roles::model()->managersAll($criteria);
        $connection = Yii::app()->db;
        $users = [];
        array_walk_recursive($managers, function ($value, $key) use (&$users) {
            $users[] = $value;
        });
        $request = [];
        $result = [];
        $count = null;
        $statuss = Status::model()->findAllByAttributes(['enabled' => 1]);
        $column[] = [
            'name' => 'user',
            'header' => Yii::t('main-ui', 'Manager'),
        ];
        $column[] = [
            'name' => 'leadTimeEx',
            'header' => Yii::t('main-ui', 'Lead time'),
        ];
        foreach ($statuss as $statusi) {
            $column[] = [
                'name' => $statusi->id,
                'header' => $statusi->name,
            ];
        }

        foreach ($users as $user) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = $statuss;
            $request['user'] = $user->fullname;
            $request['sdate'] = date('Y-m-d H:i:s', $sdate);
            $request['edate'] = date('Y-m-d H:i:s', $edate);
            $leadTime = [null];

            if (isset($company) and !empty($company)) {
                $reqs = Request::model()->findAllByAttributes([
                    'Managers_id' => $user->Username,
                    'company' => $company ? $company : null
                ], $criteria);
            } else {
                $reqs = Request::model()->findAllByAttributes([
                    'Managers_id' => $user->Username,
                ], $criteria);
            }
            foreach ($reqs as $req) {
                $leadTime[] = $req->lead_time;
            }

            foreach ($statuses as $status) {
                if (!empty($company)) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status->name.'\' AND `t`.`company`=\''. $company .'\' AND (timestamp BETWEEN \''.date('Y-m-d H:i:s', $sdate).'\' AND \''.date('Y-m-d H:i:s', $edate).'\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[$status->id] = (int)$count;
                } else {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status->name.'\' AND (timestamp BETWEEN \''.date('Y-m-d H:i:s', $sdate).'\' AND \''.date('Y-m-d H:i:s', $edate).'\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[$status->id] = (int)$count;
                }
            }
            $request['leadTimeEx'] = $this->sumTime($leadTime);
            $result[] = $request;
        }
        $config = ['pagination' => false];
        $model = new CArrayDataProvider($result, $config);
        $this->toExcel($model,
            $columns = $column,
            Yii::t('main-ui', 'Managers report') . '_' . $company_name . date('d.m.Y', $sdate) . '-' . date('d.m.Y',
                $edate),
            array(
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Managers report') . '_' . $company_name . date('d.m.Y',
                        $sdate) . '-' . date('d.m.Y', $edate),
            ),
            'Excel2007'
        );
    }

    public function actionExportManagerskpi($sdate, $edate, $company = null, $type = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sdate = $sdate ? strtotime($sdate . ' 00:00:00') : strtotime(date('d.m.Y') . '00:00:00');
        $edate = $edate ? strtotime($edate. ' 23:59:00'): strtotime(date('d.m.Y') . '23:59:59');
        $company_name = $company ? $company : 'Все компании';

        if($type == 1){
            $criteria = new CDbCriteria(['order' => 'fullname ASC']);
            if(Yii::app()->user->checkAccess('systemManager')){
                $criteria->compare('id', Yii::app()->user->id, false);
            }
            $managers = Roles::model()->managersAll($criteria);
            array_walk_recursive($managers, function ($value, $key) use (&$users) {
                $users[] = $value;
            });
            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Manager'),
            ];
            $columns[] = [
                'name' => 'rating',
                'header' => Yii::t('main-ui', 'Average rating'),
            ];
            $search = 'Managers_id';
        }

        if($type == 2){
            $criteria = new CDbCriteria(['order' => 'fullname ASC']);
            if(Yii::app()->user->checkAccess('systemUser')){
                $criteria->compare('id', Yii::app()->user->id, false);
            }
            if(isset($company) AND !empty($company)) {
                $criteria->compare('company', $company, false);
            }
            $managers = Roles::model()->usersAll($criteria);
            array_walk_recursive($managers, function ($value, $key) use (&$users) {
                $users[] = $value;
            });
            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'User'),
            ];
            $search = 'CUsers_id';
        }

        if($type == 3){
            $criteria = new CDbCriteria(['order' => 'name ASC']);
            if(isset($company) AND !empty($company)) {
                $company = Companies::model()->findByAttributes(['name' => $company]);
                $srvs = $company->getServicesArray() + Service::getAllShared();
                foreach ($srvs as $key => $value){
                    $criteria->addSearchCondition('id', $key, false, 'OR', 'LIKE');
                }
                $services = Service::model()->findAll($criteria);
                array_walk_recursive($services, function ($value, $key) use (&$users) {
                    $users[] = $value;
                });

            } else {
                $managers = Service::model()->findAll($criteria);
                array_walk_recursive($managers, function ($value, $key) use (&$users) {
                    $users[] = $value;
                });

            }

            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Service'),
            ];
            $search = 'service_id';
        }

        if($type == 4){
            $criteria = new CDbCriteria(['order' => 'name ASC']);
            $groups = Groups::model()->findAll($criteria);
            array_walk_recursive($groups, function ($value, $key) use (&$users) {
                $users[] = $value;
            });


            $columns[] = [
                'name' => 'user',
                'header' => Yii::t('main-ui', 'Group'),
            ];
            $search = 'groups_id';
        }

        $columns[] = [
            'name' => 'opened',
            'header' => Yii::t('main-ui', 'Opened tickets'),
        ];
        $columns[] = [
            'name' => 'inwork',
            'header' => Yii::t('main-ui', 'Tickets was in work'),
        ];
        $columns[] = [
            'name' => 'inwork_success',
            'header' => Yii::t('main-ui', 'Tickets was in work no overdue'),
        ];
        $columns[] = [
            'name' => 'wasclosed',
            'header' => Yii::t('main-ui', 'Tickets was closed'),
        ];
        $columns[] = [
            'name' => 'wasclosed_success',
            'header' => Yii::t('main-ui', 'Tickets was closed no overdue'),
        ];
        $columns[] = [
            'name' => 'reopened',
            'header' => Yii::t('main-ui', 'Reopened tickets'),
        ];
        $columns[] = [
            'name' => 'canceled',
            'header' => Yii::t('main-ui', 'Canceled tickets'),
        ];
        $columns[] = [
            'name' => 'delayed_start',
            'header' => Yii::t('main-ui', 'Ticket was delayed by reaction'),
        ];
        $columns[] = [
            'name' => 'delayed_end',
            'header' => Yii::t('main-ui', 'Ticket was delayed by salvation'),
        ];
        $columns[] = [
            'name' => 'delayed',
            'header' => Yii::t('main-ui', 'Pending tickets'),
        ];
        $columns[] = [
            'name' => 'waspaused',
            'header' => Yii::t('main-ui', 'Tickets was paused'),
        ];
        $columns[] = [
            'name' => 'wasautoclosed',
            'header' => Yii::t('main-ui', 'Tickets was closed automatically'),
        ];
        $columns[] = [
            'name' => 'wasescalated',
            'header' => Yii::t('main-ui', 'Tickets was escalated'),
        ];

        foreach ($users as $user) {
            if($type == 1){
                $value = $user->Username;
                $user_val = $user->fullname;
            }
            if($type == 2){
                $value = $user->Username;
                $user_val = $user->fullname;
            }
            if($type == 3){
                $value = $user->id;
                $user_val = $user->name;
            }
            if($type == 4){
                $value = $user->id;
                $user_val = $user->name;
            }

            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';

            $criteria_inwork = new CDbCriteria;
            $criteria_inwork->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fStartTime IS NOT NULL';

            $criteria_inwork_success = new CDbCriteria;
            $criteria_inwork_success->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fStartTime IS NOT NULL AND delayed_start <> 1';

            $criteria_wasclosed = new CDbCriteria;
            $criteria_wasclosed->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fEndTime IS NOT NULL';

            $criteria_wasclosed_success = new CDbCriteria;
            $criteria_wasclosed_success->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND fEndTime IS NOT NULL AND delayed_end <> 1';

            $request['id'] = $user->id;
            $request['user'] = $user_val;
            //устанавливаем атрибуты для отчета
            if (isset($company) and !empty($company)) {
                $opened = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value], $criteria);
                $inwork = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value], $criteria_inwork);
                $inwork_success = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value], $criteria_inwork_success);
                $wasclosed = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value], $criteria_wasclosed);
                $wasclosed_success = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value], $criteria_wasclosed_success);
                $reopened = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'reopened' => 1], $criteria);
                $canceled = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'canceled' => 1], $criteria);
                $delayed_start = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'delayed_start' => 1], $criteria);
                $delayed_end = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'delayed_end' => 1], $criteria);
                $delayed = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'delayed' => 1], $criteria);
                $waspaused = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'waspaused' => 1], $criteria);
                $wasautoclosed = Request::model()->countByAttributes(['company' => $company ? $company : null, $search => $value, 'wasautoclosed' => 1], $criteria);
                $wasescalated = Request::model()->countByAttributes(['company' => $company ? $_POST['Report']['company'] : null, $search => $value, 'wasescalated' => 1], $criteria);
            } else {
                $opened = Request::model()->countByAttributes([$search => $value], $criteria);
                $inwork = Request::model()->countByAttributes([$search => $value], $criteria_inwork);
                $inwork_success = Request::model()->countByAttributes([$search => $value], $criteria_inwork_success);
                $wasclosed = Request::model()->countByAttributes([$search => $value], $criteria_wasclosed);
                $wasclosed_success = Request::model()->countByAttributes([$search => $value], $criteria_wasclosed_success);
                $reopened = Request::model()->countByAttributes([$search => $value, 'reopened' => 1], $criteria);
                $canceled = Request::model()->countByAttributes([$search => $value, 'canceled' => 1], $criteria);
                $delayed_start = Request::model()->countByAttributes([$search => $value, 'delayed_start' => 1], $criteria);
                $delayed_end = Request::model()->countByAttributes([$search => $value, 'delayed_end' => 1], $criteria);
                $delayed = Request::model()->countByAttributes([$search => $value, 'delayed' => 1], $criteria);
                $waspaused = Request::model()->countByAttributes([$search => $value, 'waspaused' => 1], $criteria);
                $wasautoclosed = Request::model()->countByAttributes([$search => $value, 'wasautoclosed' => 1], $criteria);
                $wasescalated = Request::model()->countByAttributes([$search => $value, 'wasescalated' => 1], $criteria);
            }

            if($type == 1){
                if (isset($company) and !empty($company)) {
                    $criteria_rating = new CDbCriteria;
                    $criteria_rating->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '"  AND rating IS NOT NULL';
                    $rating_arr = Request::model()->findAllByAttributes(['company' => $company ? $company : null, $search => $value], $criteria_rating);
                } else {
                    $criteria_rating = new CDbCriteria;
                    $criteria_rating->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s', $edate) . '" AND rating IS NOT NULL';
                    $rating_arr = Request::model()->findAllByAttributes([$search => $value], $criteria_rating);
                }
                if(!empty($rating_arr)){
                    $rating_count = (int)count($rating_arr);
                }
                $i = (int)0;
                foreach ($rating_arr as $rating_item){
                    $i = (int)$i+(int)$rating_item->rating;
                }
                $rating = (int)$i/(int)$rating_count;
            }

            $request['opened'] = (int)$opened;
            $request['inwork'] = (int)$inwork !==0 ? (int)$inwork .' / '. round(($inwork/$opened)*100) . '%' : '0 / 0%';
            $request['inwork_success'] = (int)$inwork_success !==0 ?(int)$inwork_success.' / '. round(($inwork_success/$opened)*100) . '%' : '0 / 0%';
            $request['wasclosed'] = (int)$wasclosed !==0 ? (int)$wasclosed.' / '. round(($wasclosed/$opened)*100) . '%' : '0 / 0%';
            $request['wasclosed_success'] = (int)$wasclosed_success !==0 ?(int)$wasclosed_success.' / '. round(($wasclosed_success/$opened)*100) . '%' : '0 / 0%';
            $request['reopened'] = (int)$reopened !==0 ? (int)$reopened.' / '. round(($reopened/$opened)*100) . '%' : '0 / 0%';
            $request['canceled'] = (int)$canceled !==0 ? (int)$canceled.' / '. round(($canceled/$opened)*100) . '%' : '0 / 0%';
            $request['delayed_start'] = (int)$delayed_start !== 0 ? (int)$delayed_start.' / '. round(($delayed_start/$opened)*100) . '%' : '0 / 0%';
            $request['delayed_end'] = (int)$delayed_end !==0 ? (int)$delayed_end.' / '. round(($delayed_end/$opened)*100) . '%' : '0 / 0%';
            $request['delayed'] = (int)$delayed !==0 ? (int)$delayed.' / '. round(($delayed/$opened)*100) . '%' : '0 / 0%';
            $request['waspaused'] = (int)$waspaused !== 0 ? (int)$waspaused.' / '. round(($waspaused/$opened)*100) . '%' : '0 / 0%';
            $request['wasautoclosed'] = (int)$wasautoclosed !==0 ? (int)$wasautoclosed.' / '. round(($wasautoclosed/$opened)*100) . '%' : '0 / 0%';
            $request['wasescalated'] = (int)$wasescalated !==0 ? (int)$wasescalated.' / '. round(($wasescalated/$opened)*100) . '%' : '0 / 0%';
            $request['rating'] = (int)$rating !==0 ? (int)$rating : NULL;
            $model[] = $request;
        }
        $config = ['pagination' => false];
        $model = new CArrayDataProvider($model, $config);
        $this->toExcel($model,
            $columns = $columns,
            Yii::t('main-ui', 'KPI report') . '_' . $company_name. '_' . date('d.m.Y', $sdate) . '-' . date('d.m.Y',
                $edate),
            array(
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'KPI report') . '_' . $company_name . '_' . date('d.m.Y',
                        $sdate) . '-' . date('d.m.Y', $edate),
            ),
            'Excel2007'
        );
    }

    public function actionExportAssets($company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $assets = Cunits::model()->findAll();
        $request = [];
        $result = [];
        $company = $company ? $company . '_' : null;
        $columns = [];
        $count = null;
        $column[] = [
            'name' => 'asset',
            'header' => Yii::t('main-ui', 'Asset'),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $column[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->name,
            ];
        }

        foreach ($assets as $asset) {
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['id'] = $asset->id;
            $request['asset'] = $asset->name;
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes([
                    'cunits' => $asset->name,
                    'Status' => $status->name
                ]);
                $request[$status->name] = (int)$count;
            }
            $result[] = $request;
        }
        $config = ['pagination' => false];
        $model = new CArrayDataProvider($result, $config);
        $this->toExcel($model,
            $columns = $column,

            Yii::t('main-ui', 'Assets report') . '_' . $company . date('d.m.Y'),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Assets report') . '_' . $company . date('d.m.Y'),
            ],
            'Excel2007'
        );
    }

    public function actionExportProblems($company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $company = $company ? $company . '_' : null;
        $model = Pureport::model()->search();
        $this->toExcel($model,
            $columns = [
                'assetname',
                'assettype',
                'status',
                'stnew:html:Зарегистрирована',
                'stworkaround:html:Обходное решение',
                'stsolved:html:Решена',

            ],

            Yii::t('main-ui', 'Problems by unit') . '_' . $company . date('d.m.Y'),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Problems by unit') . '_' . $company . date('d.m.Y'),
            ],
            'Excel2007'
        );
    }

    public function actionExportRequestProblem($month, $year)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);

        $report = [];
        $services = Service::model()->findAll();


        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . $year . '-' . $month . '-01 00:00:00' . '" AND "' . $year . '-' . $month . '-31 23:59:59' . '"';
        foreach ($services as $service) {

            $model = [];
            $model['id'] = $service->id;
            $model['parent_service_id'] = $service->parent_service_id;
            $model['parent_service'] = $service->pservice_rl->name;
            $model['parent_pavailability'] = $service->pservice_rl->availability;

            $request = Request::model()->findAllByAttributes(['service_id' => $service->id], $criteria);
            $rcount = Request::model()->countByAttributes(['service_id' => $service->id], $criteria);
            $requests_count = (int)$rcount;

            $hour = '00';
            $min = '00';
            $sec = '00';

            $availability_full = 0;

            foreach ($request as $item) {
                if (!empty($item->lead_time)) {
                    list($h, $m, $s) = explode(':', $item->lead_time);
                    $hour = (int)$hour + (int)$h;
                    $min = (int)$min + (int)$m;
                    $sec = (int)$sec + (int)$s;

                    $pm = $min % 60;
                    $ph = (int)($min / 60);

                    $hh = (int)$hour + (int)$ph;
                    $mm = $pm;

                    if ($mm !== '00') {
                        $minper = 0.0166666666666667 * $mm; //переводим минуты в десятичные дроби часа
                        $minn = $minper;
                    } else {
                        $minn = 0;
                    }

                    $full = $hh + $minn; //количество часов с минутами в десятичных дробях.
                    $availability_full += ((720 - $full) / 720) * 100;
                }
            }

            $service_availability = $service->availability;

            $model['servicename'] = $service->name;
            $model['stnew'] = $requests_count;

            if ($requests_count != 0) {
                $availability = sprintf("%01.2f", ($availability_full / $requests_count));
            } else {
                $availability = 100;
            }

            $model['availability'] = $availability;
            //*$model->pavailability = $paval;
            $model['pavailability'] = $service_availability;

            $report[] = $model;
            //echo '<pre>'; var_dump($model); exit;
            //*$model->save(false);

        }

        $total_ar = [];
        foreach ($report as $row) {
            if (!isset($total_ar[$row['parent_service_id']]['availability'])) {
                $total_ar[$row['parent_service_id']]['availability'] = $row['availability'];
                $total_ar[$row['parent_service_id']]['count'] = 1;
            } else {
                $total_ar[$row['parent_service_id']]['availability'] += $row['availability'];
                $total_ar[$row['parent_service_id']]['count'] += 1;
            }

            if (!isset($total_ar[$row['parent_service_id']]['stnew'])) {
                $total_ar[$row['parent_service_id']]['stnew'] = $row['stnew'];
            } else {
                $total_ar[$row['parent_service_id']]['stnew'] += $row['stnew'];
            }
        }

        foreach ($total_ar as $key => $total) {
            if ($total['count'] == 0) {
                $total_ar[$key]['availability'] = 100;
            } else {
                $total_ar[$key]['availability'] = $total['availability'] / $total['count'];
            }
        }

        for ($i = 0; $i < count($report); $i++) {
            $report[$i]['parent_availability'] = sprintf("%01.2f",
                ($total_ar[$report[$i]['parent_service_id']]['availability']));
        }


        $company = null;
        $model = new CArrayDataProvider($report);
        $this->toExcel($model,
            $columns = [
                'parent_service:text:' . Yii::t('main-ui', 'Parent service'),
                'parent_availability:text:' . Yii::t('main-ui', 'Parent service'),
                'parent_pavailability:text:' . Yii::t('main-ui', 'Availability % (SLA)'),
                'servicename:text:' . Yii::t('main-ui', 'Service'),
                'stnew:text:' . Yii::t('main-ui', 'Requests'),
                'availability:text:' . Yii::t('main-ui', 'Availability %'),
                'pavailability:text:' . Yii::t('main-ui', 'Availability % (SLA)'),
            ],

            Yii::t('main-ui', 'Service request report by month') . '_' . $company . date('d.m.Y'),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Service request report by month') . '_' . $company . date('d.m.Y'),
            ],
            'Excel2007'
        );
    }

    public function actionExportCustom()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        //Yii::app()->session['requestPageCount'] = false;
        $columns = [];
        if (isset(Yii::app()->session['customReportColumns'])) {
            foreach (Yii::app()->session['customReportColumns'] as $column) {
                $columns[] = ($column['name'] == 'slabel') ? 'Status' : $column['name'];
            }
        }
        //echo '<pre>';
        //var_dump(Yii::app()->session['customReport']);exit;
        $model = new Request('searchcustom');
        $model->unsetAttributes();
        $model->attributes = Yii::app()->session['customReport'];
        $dp = $model->searchcustom();

        $this->toExcel($dp->getData(),
            $columns,
            'Сводный отчет',
            [
                'creator' => 'Univef',
                'title' => 'Сводный отчет',
            ],
            'Excel2007'
        );
        unset(Yii::app()->session['customReport']);
    }

    public function actionExportService($sdate, $edate, $company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (!empty($company)) {
            $allServices = [];
            $companye = Companies::model()->findByAttributes(['name' => $company]);
            if ($companye) {
                $companyServices = $companye->getServicesArray();
                foreach ($companyServices as $key => $value) {
                    if (!isset($allServices[$key])) {
                        $allServices[$key] = $value;
                    }
                }
            }
            /** @var Depart $depart */
            $depart = Depart::model()->findAllByAttributes(['company' => $company]);
            if ($depart) {
                foreach ($depart as $item) {
                    $dep = Depart::model()->findByPk($item->id);
                    $departServices = $dep->getServicesArray();
                    foreach ($departServices as $key => $value) {
                        if (!isset($allServices[$key])) {
                            $allServices[$key] = $value;
                        }
                    }
                }

            }
            foreach ($allServices as $service => $value) {
                $services[] = Service::model()->findByPk($service);
            }
        } else {
            $services = Service::model()->findAll();
        }
        $request = [];
        $result = [];
        $column = [];
        $count = null;
        $company = $company ? $company . '_' : null;
        $column[] = [
            'name' => 'service',
            'header' => Yii::t('main-ui', 'Service'),
        ];
        $status_all = Status::model()->findAllByAttributes(['enabled' => 1]);
        foreach ($status_all as $stat_all) {
            $column[] = [
                'name' => $stat_all->name,
                'header' => $stat_all->name,
            ];
        }

        foreach ($services as $service) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                    $edate) . '"';
            $statuses = Status::model()->findAllByAttributes(['enabled' => 1]);
            $request['id'] = $service->id;
            $request['service'] = $service->name;
            foreach ($statuses as $status) {
                $count = Request::model()->countByAttributes([
                    'service_id' => $service->id,
                    'Status' => $status->name
                ], $criteria);
                $request[$status->name] = (int)$count;
            }
            $result[] = $request;
        }
        $config = array('pagination' => false);
        $model = new CArrayDataProvider($result, $config);
        $this->toExcel($model,
            $columns = $column,

            Yii::t('main-ui', 'Service report') . '_' . $company . date('d.m.Y', $sdate) . ' - ' . date('d.m.Y',
                $edate),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Service report') . '_' . $company . date('d.m.Y',
                        $sdate) . ' - ' . date('d.m.Y', $edate),
            ],
            'Excel2007'
        );
    }

    public function actionExportSproblem($month, $year)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = Psreport::model()->search();
        $this->toExcel($model,
            $columns = [
                'servicename',
                'stnew:html:Зарегистрирована',
                'stworkaround:html:Обходное решение',
                'stsolved:html:Решена',
                'downtime:html:Время простоя сервиса',
                'availability:html:Доступность %',
                'pavailability:html:Доступность % (SLA)',
            ],

            Yii::t('main-ui', 'Service problems report') . '_' . $month . '_' . $year,
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Service problems report') . '_' . $month . '_' . $year,
            ],
            'Excel2007'
        );
    }

    public function actionExportSproblem2($sdate, $edate, $company = null)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $company = $company ? $company . '_' : null;
        $model = Psreport::model()->search();
        $this->toExcel($model,
            $columns = [
                'servicename',
                'stnew:html:Зарегистрирована',
                'stworkaround:html:Обходное решение',
                'stsolved:html:Решена',
            ],

            Yii::t('main-ui', 'Service problems report') . '_' . $company . date('d.m.Y', $sdate) . '_' . date('d.m.Y',
                $edate),
            [
                'creator' => 'Univef',
                'title' => Yii::t('main-ui', 'Service problems report') . '_' . $company . date('d.m.Y',
                        $sdate) . '_' . date('d.m.Y', $edate),
            ],
            'Excel2007'
        );
    }

    /**
     * Сводный отчет.
     */
    public function actionCustomReport()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['customPageCount'] = $_GET['pageCount'];
        }
        $model = new Request('searchcustom');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Request'])) {
            $model->attributes = $_GET['Request'];
            Yii::app()->session['customReport'] = $_GET['Request'];
            if (isset($_GET['Request']['slabel'])) {
                Yii::app()->session['customReport']['Status'] = $_GET['Request']['slabel'];
                unset(Yii::app()->session['customReport']['slabel']);
            }
            if (Yii::app()->session['customReport']['delayed_start']) {
                $model->delays[] = 'delayed_start';
                $model->delayed_start = 1;
            }
            if (Yii::app()->session['customReport']['delayed_end']) {
                $model->delays[] = 'delayed_end';
                $model->delayed_end = 1;
            }
        }

        $this->render('customreport', [
            'model' => $model,
        ]);
    }

    public function actionAllFields()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Report;
        Yii::app()->user->setFlash('info',
            Yii::t('main-ui', '<strong>Request by service report!</strong> To generate a report, select the period!'));
        $this->render('allfields', [
            'model' => $model,
        ]);
    }

    public function actionAllFieldsReport()
    {
        if (isset($_POST['Report']['service'])) {
            Yii::app()->session['reportService'] = $_POST['Report']['service'];
        } else {
            $_POST['Report']['service'] = Yii::app()->session['reportService'];
        }

        if (isset($_POST['Report']['company'])) {
            Yii::app()->session['reportCompany'] = $_POST['Report']['company'];
        } else {
            $_POST['Report']['company'] = Yii::app()->session['reportCompany'];
        }

        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['requestPageCount'] = $_GET['pageCount'];
        }

        if (isset($_POST['Report']['sdate'])) {
            $sdate = strtotime($_POST['Report']['sdate'] . ' 00:00:00');
        } else {
            $sdate = strtotime(date('d.m.Y') . ' 00:00:00');
        }

        if (isset($_POST['Report']['sdate'])) {
            $edate = strtotime($_POST['Report']['edate'] . ' 23:59:00');
        } else {
            $edate = strtotime(date('d.m.Y') . ' 00:00:00');
        }

        $s_id = $_POST['Report']['service'];
        $company = $_POST['Report']['company'];

        $comp_search = $_POST['Report']['company'] ? "`company`='$company' AND " : '';
        $service_search = $_POST['Report']['service'] ? "`service_id`='$s_id' AND " : '';

        //$service = Service::model()->findByPk(1);
        $criteria = new CDbCriteria;
        $criteria->condition = " $comp_search $service_search timestamp BETWEEN '" . date('Y-m-d H:i:s',
                $sdate) . "' AND '" . date('Y-m-d H:i:s', $edate) . "'";
        //$request = Request::model()->findAllByAttributes(array('service_id' => $s_id, 'company' => $company), $criteria);

        $request = new CActiveDataProvider('Request', [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 'id DESC',
            ],
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30,
            ],
        ]);
        $this->render('allfieldsreport',
            ['gridDataProvider' => $request, 'service_id' => $s_id, 'sdate' => $sdate, 'edate' => $edate]);
    }

    public function actionExportAllFields($service, $sdate, $edate)
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $criteria = new CDbCriteria;
        $criteria->condition = 'timestamp BETWEEN "' . date('Y-m-d H:i:s', $sdate) . '" AND "' . date('Y-m-d H:i:s',
                $edate) . '"';
        if (isset($service) AND !empty($service)) {
            $request = Request::model()->findAllByAttributes(['service_id' => $service], $criteria);
        } else {
            $request = Request::model()->findAll($criteria);
        }


        $columns = [];
        if (isset(Yii::app()->session['allFieldsReportColumns'])) {
            foreach (Yii::app()->session['allFieldsReportColumns'] as $column) {
                $columns[] = ($column['name'] == 'slabel') ? 'Status' : $column;
            }
        }

        $sname = Service::model()->findByPk($service);

        $this->toExcel($request,
            $columns,
            $sname->name,
            [
                'creator' => 'Univef',
                'title' => $sname->name,
            ],
            'Excel2007'
        );
    }
}
