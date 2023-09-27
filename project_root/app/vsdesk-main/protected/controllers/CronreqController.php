<?php

class CronreqController extends Controller
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
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'selectPriority'),
                'roles' => array('listCronRequest'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateCronRequest'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createCronRequest'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteCronRequest'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new CronReq;
        //var_dump($_POST);
        // die;
        if (isset($_POST['CronReq'])) {
            $model->attributes = $_POST['CronReq'];
            if ($model->save()) {
                if (isset($_POST['Request'])) {
                    $fid = $_POST['CronReq']['service_id'];
                    $service = Service::model()->findByPk($fid);
                    $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset));
                    foreach ($fields as $field) {
                        if (isset($_POST['Request'][$field->id])) {
                            $json[] = [
                                'id' => $field->id,
                                'name' => $field->name,
                                'type' => $field->type,
                                'value' => $_POST['Request'][$field->id],
                            ];
                        }
                    }
                    CronReq::model()->updateByPk($model->id, array('fields' => json_encode($json)));
                }
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);


        if (isset($_POST['CronReq'])) {
            $model->attributes = $_POST['CronReq'];
            $model->sla = $_POST['CronReq']['sla'];
            
            $model->service_id = $_POST['CronReq']['service_id'];
            // $model->service = Service::model()->findByPk($_POST['CronReq']['service_id']);
            // var_dump($_POST['CronReq']['service_id'], $model);
            // die();
            if ($model->save())
                if (isset($_POST['Request'])) {
                    $fid = $model->service_id;
                    $service = Service::model()->findByPk($fid);
                    $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset));
                    foreach ($fields as $field) {
                        if (isset($_POST['Request'][$field->id])) {
                            $json[] = [
                                'id' => $field->id,
                                'name' => $field->name,
                                'type' => $field->type,
                                'value' => $_POST['Request'][$field->id],
                            ];
                        }
                    }
                    CronReq::model()->updateByPk($id, array('fields' => json_encode($json)));
                }
            //     var_dump($model->service_id);
            // die();
            $this->redirect(array('index'));
            // $this->render('update', array(
            //     'model' => $model,
            // ));
        }
        if (!empty($model->cunits))
            $model->cunits = explode(',', $model->cunits);

        if (!empty($model->watchers))
            $model->watchers = explode(',', $model->watchers);
        // var_dump($model);
        // die();
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = CronReq::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        // Админ
        // $allCronReqs = CronReq::model()->findAllByAttributes(['enabled' => 1]);

        $model = new CronReq('search');
        $model->unsetAttributes();
        $model->enabled = 1;
        $dp = $model->search();
        $allCronReqs = $dp->getData();

        $json = [];
        if (!empty($allCronReqs)) {
            foreach ($allCronReqs as $cronReq) {
                /** @var $cronReq CronReq */

                if ($cronReq->repeats == 0)
                    $json[] = [
                        'id' => $cronReq->id,
                        'title' => $cronReq->Name,
                        'start' => date("H:i", strtotime($cronReq->Date)),
                        'end' => date("H:i", strtotime($cronReq->Date . "+1 hour")),
                        'color' => $cronReq->color ? $cronReq->color : '#f56954',
                        'allDay' => false,
                        'dow' => [0, 1, 2, 3, 4, 5, 6],
                        'ranges' => [
                            [
                                'start' => date("Y-m-d H:m:i", strtotime($cronReq->Date)),
                                'end' => date("Y-m-d", strtotime($cronReq->Date)) . ' 23:59:59',
                            ]
                        ]
                    ];
                if ($cronReq->repeats == 1) /* Повторять каждый день */
                    $json[] = [
                        'id' => $cronReq->id,
                        'title' => $cronReq->Name,
                        'start' => date("H:i", strtotime($cronReq->Date)),
                        'end' => date("H:i", strtotime($cronReq->Date . "+1 hour")),
                        'allDay' => false,
                        'color' => $cronReq->color ? $cronReq->color : '#5692bb',
                        'dow' => [0, 1, 2, 3, 4, 5, 6],
                        'ranges' => [
                            [
                                'start' => date("Y-m-d H:m:i", strtotime($cronReq->Date)),
                                'end' => date("Y-m-d H:m:i", strtotime($cronReq->Date_end))
                            ]
                        ],
                    ];
                elseif ($cronReq->repeats == 2) /* Повторять раз в неделю */
                    $json[] = [
                        'id' => $cronReq->id,
                        'title' => $cronReq->Name,
                        'start' => date("H:i", strtotime($cronReq->Date)),
                        'end' => date("H:i", strtotime($cronReq->Date . "+1 hour")),
                        'allDay' => false,
                        'color' => $cronReq->color ? $cronReq->color : '#6ac28e',
                        'dow' => [intval(date('w', strtotime($cronReq->Date)))],
                        'ranges' => [
                            [
                                'start' => date("Y-m-d H:i", strtotime($cronReq->Date)),
                                'end' => date("Y-m-d H:m:i", strtotime($cronReq->Date_end))
                            ]
                        ],
                    ];
                elseif ($cronReq->repeats == 3) { /* Повторять раз в месяц */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numMonths = $interval->format('%m');
                    for ($i = 0; $i <= $numMonths; $i++) {
                        $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " month"));
                        $json[] = [
                            'id' => $cronReq->id,
                            'title' => $cronReq->Name,
                            'start' => $start,
                            'end' => '23:00',
                            'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                            'allDay' => false,
                            'ranges' => [
                                [
                                    'start' => date('Y-m-d H:m:i', strtotime($date)),
                                    'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                ]
                            ]
                        ];
                    }
                }
                elseif ($cronReq->repeats == 5) { /* Повторять раз в 2 дня */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numDays = $interval->days;
                    for ($i = 0; $i <= $numDays; $i++) {
                        if ($i % 2 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " days"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 6) { /* Повторять раз в 3 дня */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numDays = $interval->days;
                    for ($i = 0; $i <= $numDays; $i++) {
                        if ($i % 3 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " days"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 7) { /* Повторять раз в 4 дня */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numDays = $interval->days;
                    for ($i = 0; $i <= $numDays; $i++) {
                        if ($i % 4 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " days"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 8) { /* Повторять раз в 5 дней */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numDays = $interval->days;
                    for ($i = 0; $i <= $numDays; $i++) {
                        if ($i % 5 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " days"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 9) { /* Повторять раз в 6 дней */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numDays = $interval->days;
                    for ($i = 0; $i <= $numDays; $i++) {
                        if ($i % 6 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " days"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 10) { /* Повторять раз в 2 недели */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numWeeks = floor($interval->days / 7);
                    for ($i = 0; $i <= $numWeeks; $i++) {
                        if ($i % 2 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " weeks"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 11) { /* Повторять раз в 3 недели */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numWeeks = floor($interval->days / 7);
                    for ($i = 0; $i <= $numWeeks; $i++) {
                        if ($i % 3 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " weeks"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 12) { /* Повторять раз в 2 месяца */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numMonths = $interval->format('%m');
                    for ($i = 0; $i < $numMonths; $i++) {
                        if ($i % 2 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " months"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 13) { /* Повторять раз в 3 месяца */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numMonths = $interval->format('%m');
                    for ($i = 0; $i <= $numMonths; $i++) {
                        if ($i % 3 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " months"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 14) { /* Повторять раз в 4 месяца */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numMonths = $interval->format('%m');
                    for ($i = 0; $i <= $numMonths; $i++) {
                        if ($i % 4 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " months"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 15) { /* Повторять раз в 5 месяцев */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numMonths = $interval->format('%m');
                    for ($i = 0; $i <= $numMonths; $i++) {
                        if ($i % 5 == 0) {
                            $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " months"));
                            $json[] = [
                                'id' => $cronReq->id,
                                'title' => $cronReq->Name,
                                'start' => $start,
                                'end' => '23:00',
                                'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                                'allDay' => false,
                                'ranges' => [
                                    [
                                        'start' => date('Y-m-d H:m:i', strtotime($date)),
                                        'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                    ]
                                ]
                            ];
                        }
                    }
                }
                elseif ($cronReq->repeats == 4) { /* Повторять раз в год */
                    $start = date('H:i', strtotime($cronReq->Date));
                    $startDate = new DateTime($cronReq->Date);
                    $endDate = new DateTime($cronReq->Date_end);
                    $interval = $startDate->diff($endDate);
                    $numYears = $interval->y;
                    for ($i = 0; $i <= $numYears; $i++) {
                        $date = date("Y-m-d", strtotime($cronReq->Date . "+" . $i . " year"));
                        $json[] = [
                            'id' => $cronReq->id,
                            'title' => $cronReq->Name,
                            'start' => $start,
                            'end' => '23:00',
                            'color' => $cronReq->color ? $cronReq->color : '#ff851b',
                            'allDay' => false,
                            'ranges' => [
                                [
                                    'start' => date('Y-m-d H:m:i', strtotime($date)),
                                    'end' => date('Y-m-d', strtotime($date)) . ' 23:59:59',
                                ]
                            ]
                        ];
                    }
                }
            }
        }

//var_dump(json_encode($json));
        $model->unsetAttributes();
        if (isset($_GET['CronReq']))
            $model->attributes = $_GET['CronReq'];

        $this->render('index', array(
            'model' => $model,
            'json' => json_encode($json),
        ));
    }

    public function actionSelectPriority()
    {
        $priority = NULL;
        $priority = Service::model()->findByPk($_POST['CronReq']['service_id']);
        $options = NULL;
        $data = Zpriority::model()->findAllByAttributes(array('name' => $priority->priority));
        $data2 = Zpriority::model()->findAll();
        $data3 = array_merge($data, $data2);
        $data = CHtml::listData($data3, 'name', 'name');
        foreach ($data as $value => $name) {
            $options .= CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
        echo CJSON::encode(array(
            'options' => $options,
            'fid' => $priority->fieldset,
            'content' => $priority->content,
            'description' => $priority->description,
            'watcher' => explode(',', $priority->watcher),
            'csrf' => Yii::app()->request->csrfToken,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cron-req-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
