<?php

/**
 * This is the model class for table "asset_attrib".
 *
 * The followings are the available columns in table 'asset_attrib':
 * @property integer $id
 * @property string $name
 * @property integer $asset_id
 *
 * The followings are the available model relations:
 * @property AssetAttribValue[] $assetAttribValues
 */
class AssetAttrib extends CActiveRecord
{
    public static
    function all()
    {
        $models = self::model()->findAll(array('order' => 'name'));
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->id] = $aid->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AssetAttrib the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static
    function types()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public
    function tableName()
    {
        return 'asset_attrib';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public
    function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('asset_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 200),
            array('name', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, asset_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public
    function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'assetAttribValues' => array(self::HAS_MANY, 'AssetAttribValue', 'asset_attrib_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public
    function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'asset_id' => 'Asset',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public
    function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('asset_id', $this->asset_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 15),
        ));
    }

}