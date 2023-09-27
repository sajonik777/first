<?php

/**
 * This is the model class for table "request_matching_reaction".
 *
 * The followings are the available columns in table 'request_matching_reaction':
 * @property integer $id
 * @property integer $request_id
 * @property integer $iteration
 * @property integer $user_id
 * @property integer $checked
 * @property string $reaction_time
 *
 * The followings are the available model relations:
 * @property Request $request
 * @property Cusers $user
 */
class RequestMatchingReaction extends CActiveRecord
{
    const REACTION_AGREED = 1;
    const REACTION_DENIED = 2;
    const REACTION_ADD_INFO = 3;

    const REACTION_LABELS = [
        self::REACTION_AGREED => 'Согласовано',
        self::REACTION_DENIED => 'Отказано',
        self::REACTION_ADD_INFO => 'Требуется дополнительная информация',
    ];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_matching_reaction';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['request_id, iteration, user_id', 'required'],
            ['request_id, iteration, user_id, checked', 'numerical', 'integerOnly' => true],
            ['reaction_time', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, request_id, iteration, user_id, checked, reaction_time', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @param $request
     *
     * @return array
     */
    public static function getRequestStats($request)
    {
        $ret = [];

        $matchings = RequestMatchingReaction::model()->findAllByAttributes(['request_id' => $request->id]);
        if (empty($matchings)) {
            return $ret;
        }

        $params = [':request_id' => $request->id];
        $iteration = (int)yii::app()->db
            ->createCommand('select max(iteration) from request_matching_reaction where request_id = :request_id')
            ->queryScalar($params);

        $ret['total']['all'] = 0;
        $ret['total']['checked'] = 0;
        foreach ($matchings as $matching) {

            if($matching->iteration == $iteration) {
                $ret['matching'][$matching->checked][] = $matching->user->fullname;
            }

            $ret['total']['all']++;
            if($matching->checked != 0) {
                $ret['total']['checked']++;
            }
        }

        return $ret;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'request' => [self::BELONGS_TO, 'Request', 'request_id'],
            'user' => [self::BELONGS_TO, 'Cusers', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request',
            'iteration' => 'Iteration',
            'user_id' => 'User',
            'checked' => 'Checked',
            'reaction_time' => 'Reaction Time',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('request_id', $this->request_id);
        $criteria->compare('iteration', $this->iteration);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('checked', $this->checked);
        $criteria->compare('reaction_time', $this->reaction_time, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestMatchingReactionPageCount'] ? Yii::app()->session['RequestMatchingReactionPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestMatchingReaction the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
