<?php

/**
 * This is the model class for table "roles_rights".
 *
 * The followings are the available columns in table 'roles_rights':
 * @property integer $id
 * @property integer $rid
 * @property string $rname
 * @property string $name
 * @property integer $value
 *
 * The followings are the available model relations:
 * @property Roles $r
 */
class RolesRights extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RolesRights the static model class
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
        return 'roles_rights';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            array('id, rid, value', 'numerical', 'integerOnly' => true),
            array('rname, name, description, category', 'length', 'max' => 70),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, rid, rname, name, value, category', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'r' => array(self::BELONGS_TO, 'Roles', 'rid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'rid' => 'Rid',
            'rname' => 'Rname',
            'name' => Yii::t('main-ui', 'Role'),
            'category' => 'Category',
            'description' => 'description',
            'value' => 'Value',
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
        $criteria->compare('rid', $this->rid);
        $criteria->compare('rname', $this->rname, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('value', $this->value);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
