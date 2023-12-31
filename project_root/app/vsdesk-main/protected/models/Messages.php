<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $author
 * @property string $name
 * @property string $content
 * @property string $date
 */
class Messages extends CActiveRecord
{
    public $static;

    /**
     * @return array
     */
    public static function all()
    {
        $models = self::model()->findAllByAttributes(['static'=>0]);
        $array = [];
        foreach ($models as $two) {
            $array[$two->name] = $two->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return News the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'messages';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'length', 'max' => 50],
            ['subject', 'length', 'max' => 500],
            ['content', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, content, static', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'subject' => Yii::t('main-ui', 'Subject'),
            'content' => Yii::t('main-ui', 'Content'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('content', $this->content, true);
        $criteria->addInCondition('static', [null], 'OR');
        $criteria->addInCondition('static', [0], 'OR');
        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['messPageCount'] ? Yii::app()->session['messPageCount'] : 30,
            ],
        ]);
    }

    /**
     * @return CActiveDataProvider
     */
    public function searchstatic()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->addInCondition('static', [1,2,3,4], 'OR');

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
