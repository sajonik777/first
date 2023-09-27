<?php

/**
 * This is the model class for table "asset_values".
 *
 * The followings are the available columns in table 'asset_values':
 * @property integer $id
 * @property integer $asset_id
 * @property integer $asset_attrib_id
 * @property string $asset_attrib_name
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Asset $asset
 */
class AssetValues extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AssetValues the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public
    function tableName()
    {
        return 'asset_values';
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
            array('asset_id, asset_attrib_id', 'numerical', 'integerOnly' => true),
            array('asset_attrib_name, value', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, asset_id, asset_attrib_id, asset_attrib_name, value', 'safe', 'on' => 'search'),
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
            'asset' => array(self::BELONGS_TO, 'Asset', 'asset_id'),
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
            'asset_id' => 'Asset',
            'asset_attrib_id' => 'Asset Attrib',
            'asset_attrib_name' => 'Asset Attrib Name',
            'value' => 'Value',
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
        $criteria->compare('asset_id', $this->asset_id);
        $criteria->compare('asset_attrib_id', $this->asset_attrib_id);
        $criteria->compare('asset_attrib_name', $this->asset_attrib_name, true);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}