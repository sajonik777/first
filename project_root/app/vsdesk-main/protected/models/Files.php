<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property integer $id
 * @property string $name
 * @property string $file_name
 * @property string $created_at
 *
 * @property RequestFiles $requestFile
 * @property CommentFiles $commentFile
 * @property KnowledgeFiles $knowledgeFile
 * @property ProblemFiles $problemFile
 *
 * @property CUploadedFile $uploadFile
 */
class Files extends CActiveRecord
{
    /** @var CUploadedFile */
    public $uploadFile;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'files';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'uploadFile',
                'file',
                'types' => Yii::app()->params->extensions,
                'maxSize' => ((int)Yii::app()->params->max_file_size * 1024),
                'safe' => false
            ),
            array('name, file_name, created_at', 'required'),
            array('name', 'length', 'max' => 128),
            array('file_name', 'length', 'max' => 32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, file_name, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'problemFile' => array(self::HAS_ONE, 'ProblemFiles', 'file_id'),
            'knowledgeFile' => array(self::HAS_ONE, 'KnowledgeFiles', 'file_id'),
            'contractsFile' => array(self::HAS_ONE, 'ContractsFiles', 'file_id'),
            'commentFile' => array(self::HAS_ONE, 'CommentFiles', 'file_id'),
            'requestFile' => array(self::HAS_ONE, 'RequestFiles', 'file_id'),
            'assetFile' => array(self::HAS_ONE, 'AssetFiles', 'file_id'),
            'cunitsFile' => array(self::HAS_ONE, 'CunitsFiles', 'file_id'),
            'companiesFile' => array(self::HAS_ONE, 'CompaniesFiles', 'file_id'),
            'request' => array(self::HAS_ONE, 'Request', 'request_files(file_id, request_id)'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'file_name' => 'File Name',
            'created_at' => 'Created At',
        );
    }

    /**
     * @return array
     */
    public function upload()
    {
        // Если скришот
        if ($this->uploadFile->name === 'blob') {
            return $this->uploadScreen();
        }

        if ($this->validate(['uploadFile'])) {
            $fileName = uniqid('', false) . '.' . $this->uploadFile->extensionName;
            $filePath = __DIR__ . '/../../uploads/' . $fileName;
            if ($this->uploadFile->saveAs($filePath)) {
                $this->name = $this->uploadFile->name;
                $this->file_name = $fileName;
                $this->save(false);

                $result = ['url' => '//' . $_SERVER['HTTP_HOST'] . '/uploads/' . $fileName];
                $result['name'] = $this->name;
                $result['id'] = $this->id;
            } else {
                $result = [
                    'error' => true,
                    'message' => 'ERROR_CAN_NOT_UPLOAD_FILE',
                ];
            }
        } else {
            $result = [
                'error' => true,
                'message' => $this->getError('uploadFile'),
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    private function uploadScreen()
    {
        $fileName = uniqid('', false) . '.png';
        $filePath = __DIR__ . '/../../uploads/' . $fileName;
        if ($this->uploadFile->saveAs($filePath)) {
            $this->name = 'screen.png';
            $this->file_name = $fileName;
            $this->save(false);

            $result = ['url' => '//' . $_SERVER['HTTP_HOST'] . '/uploads/' . $fileName];
            $result['name'] = $this->name;
            $result['id'] = $this->id;
        } else {
            $result = [
                'error' => true,
                'message' => 'ERROR_CAN_NOT_UPLOAD_FILE',
            ];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        // Удаляем загруженные файлы перед удалением сущности
        @unlink(__DIR__ . '/../../uploads/' . $this->file_name);
        return parent::beforeDelete();
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
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['FilesPageCount'] ? Yii::app()->session['FilesPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Files the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
