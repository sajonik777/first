<?php

/**
 * This is the model class for table "chat".
 *
 * The followings are the available columns in table 'chat':
 * @property integer $id
 * @property string $created
 * @property string $name
 * @property string $reader
 * @property string $message
 * @property integer $rstate
 */
class Chats extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'chat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created, name, message', 'required'),
            array('rstate', 'numerical', 'integerOnly' => true),
            array('name, reader', 'length', 'max' => 32),
            array('message', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created, name, reader, message, rstate', 'safe', 'on' => 'search'),
        );
    }

    /**
     *
     */
    public function setRead()
    {
        try {
            $cr = new ChatRead();
            $cr->chat = $this->id;
            $cr->user = Yii::app()->user->id;
            $cr->save(false);
        } catch (Exception $e) {}
    }

    /**
     * @param $user
     * @return string
     */
    public static function getCountNewChats()
    {
        $allChats = Chats::model()->findAllByAttributes(['reader' => NULL]);
        $count = 0;
        foreach ($allChats as $chat) {
            /* @var $chat Chats */
            $posts = ChatRead::model()->countByAttributes(['chat' => $chat->id, 'user' => Yii::app()->user->id]);
            if ($posts) $count++;
        }

        return count($allChats) - $count;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user_read' => array(self::HAS_MANY, 'CUsers', 'chat_read(chat, user)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'created' => 'Created',
            'name' => 'Name',
            'reader' => 'Reader',
            'message' => 'Message',
            'rstate' => 'Rstate',
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
        $criteria->compare('created', $this->created, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('reader', $this->reader, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('rstate', $this->rstate);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['ChatsPageCount'] ? Yii::app()->session['ChatsPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chats the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
