<?php

/**
 * This is the model class for table "tcategory".
 *
 * The followings are the available columns in table 'tcategory':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $enabled
 */
class Tcategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tcategory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('parent_id, enabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, name, enabled', 'safe', 'on'=>'search'),
		);
	}

	public function beforeDelete(){
		$connection = Yii::app()->db;
        $sql = 'WITH RECURSIVE cte AS (
			SELECT id FROM tcategory WHERE parent_id ='.$this->id.'
			UNION ALL
			SELECT tcategory.id 
			FROM tcategory 
			JOIN cte ON tcategory.parent_id = cte.id -- continue down the tree
		  )
		  SELECT id FROM cte;';

        $models = $connection->createCommand($sql)->queryAll();
        foreach ($models as $one) {
			$child = Tcategory::model()->findByPk($one['id']);
			if ($child != NULL){
				$child->delete();
			}
        }
		return parent::beforeDelete();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => Yii::t('main-ui', 'Parent'),
			'name' => Yii::t('main-ui', 'Name'),
			'enabled' => Yii::t('main-ui', 'Enabled'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('enabled',$this->enabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
			'pageSize' => (int)Yii::app()->session['TcategoryPageCount'] ? Yii::app()->session['TcategoryPageCount'] : 30,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tcategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static $category_list;


	public function getTcategories($model){

        $categories = TCategory::model()->findAll();

        self::$category_list[0] = '';
		if ($model->id !== NULL) {
				$parents = ($this->allParentsTree($model->id));
				foreach ($categories as $value){
					if($value->id != $model->id && ! in_array($value->id, $parents)){

						self::$category_list[$value->id] = $value->name;

						// self::dropDownTree($categories, $value->id, $i, $model);
					}
				}
		}
		else {
			
			foreach ($categories as $value){
				self::$category_list[$value->id] = $value->name;
			}
		}	

        return self::$category_list;

    }


    protected function dropDownTree($array, $parent_id, $i, $model){

        foreach ($array as $item){

            if($item->id != $parent_id && $item->parent_id == $parent_id && $item->id != $model->id){

                self::$category_list[$item->id] = "-----" * $i . $item->name;

                self::dropDownTree($array, $item->id, $i++, $model);

            }

        }

    }

	public static
    function allTree()
    {
        $connection = Yii::app()->db;
        $sql = 'with recursive cte as (
			select id, parent_id,name, 1 lvl from tcategory
			union all
			select c.id, t.parent_id, t.name, lvl + 1
			from cte c
			inner join tcategory t on t.id = c.parent_id
		)
		select group_concat(name order by lvl desc SEPARATOR " -> ") nameTree
		from cte
		group by id';

		// with recursive cte as (
		// 	select id, parent_id,name, 1 lvl from tcategory where id = 4
		// 	union all
		// 	select c.id, t.parent_id, t.name, lvl + 1
		// 	from cte c
		// 	inner join tcategory t on t.id = c.parent_id
		// )
		// select name, group_concat(name order by lvl desc) all_parents
		// from cte
		// group by id
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['nameTree']] = $one['nameTree'];
        }
        return $array;
    }

	public static
    function allParentsTree($id)
    {
        $connection = Yii::app()->db;
        $sql = 'WITH RECURSIVE cte AS (
			SELECT id
			FROM tcategory
			WHERE id = '.$id.' -- replace with the ID of the parent node you want to query
			UNION ALL
			SELECT child.id
			FROM tcategory child
			JOIN cte ON child.parent_id = cte.id
		  )
		  SELECT id FROM cte;';

		// with recursive cte as (
		// 	select id, parent_id,name, 1 lvl from tcategory where id = 4
		// 	union all
		// 	select c.id, t.parent_id, t.name, lvl + 1
		// 	from cte c
		// 	inner join tcategory t on t.id = c.parent_id
		// )
		// select name, group_concat(name order by lvl desc) all_parents
		// from cte
		// group by id
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[] = $one['id'];
        }
        return $array;
    }

	public static
    function auall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $aid) {
            $array[] = $aid->name;
        }
        return $array;
    }

    public static
    function uuall($user)
    {
        $models = self::model()->findAllByAttributes(array('user' => $user));
        $array = array();
        foreach ($models as $aid) {
            $array[] = $aid->name;
        }
        return $array;
    }
    


}
