<?php

/**
 * Class ChecklistWidget
 */
class ChecklistWidget extends CWidget
{
    /** @var int */
    public $request_id;

    /**
     * Запуск виджета
     */
    public function run()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('request_id', $this->request_id);
        $criteria->order = 'sorting ASC';

        $sort = new CSort();
        $sort->attributes = ['sorting' => 'sorting ASC'];
        $dataProvider = new CActiveDataProvider('RequestChecklistFields', [
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => false,
        ]);
        $dataProvider->sort->defaultOrder = 'sorting ASC';

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
