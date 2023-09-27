<?php

/**
 * This is the model class for table "pstatus".
 *
 * The followings are the available columns in table 'pstatus':
 * @property integer $id
 * @property string $name
 * @property string $label
 */
class Astatus extends CActiveRecord
{
    public static
    function all()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Pstatus the static model class
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
        return 'astatus';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, tag', 'required'),
            array('name, tag', 'length', 'max' => 50),
            array('label', 'length', 'max' => 400),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, label, tag', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('main-ui' , 'Name'),
            'label' => Yii::t('main-ui' , 'Label'),
            'tag' => Yii::t('main-ui' , 'Label'),
        );
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
        $criteria->compare('label', $this->label, true);
        $criteria->compare('tag', $this->tag, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => (int)Yii::app()->session['astatusPageCount'] ? Yii::app()->session['astatusPageCount'] : 30,),
        ));
    }

    public function beforeSave()
    {

        $this->label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: '.$this->tag .'; vertical-align: baseline; white-space: nowrap; border: 1px solid '.$this->tag .'; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $this->name . '</span>';

        return parent::beforeSave();
    }

}