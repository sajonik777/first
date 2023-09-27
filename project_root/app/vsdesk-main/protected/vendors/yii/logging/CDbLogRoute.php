<?php
/**
 * CDbLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CDbLogRoute stores log messages in a database table.
 *
 * To specify the database table for storing log messages, set {@link logTableName} as
 * the name of the table and specify {@link connectionID} to be the ID of a {@link CDbConnection}
 * application component. If they are not set, a SQLite3 database named 'log-YiiVersion.db' will be created
 * and used under the application runtime directory.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class CDbLogRoute extends CLogRoute
{
	/**
	 * @var string the ID of CDbConnection application component. If not set, a SQLite database
	 * will be automatically created and used. The SQLite database file is
	 * <code>protected/runtime/log-YiiVersion.db</code>.
	 */
	public $connectionID;
	/**
	 * @var string the name of the DB table that stores log content. Defaults to 'YiiLog'.
	 * If {@link autoCreateLogTable} is false and you want to create the DB table manually by yourself,
	 * you need to make sure the DB table is of the following structure:
	 * <pre>
	 *  (
	 *		id       INTEGER NOT NULL PRIMARY KEY,
	 *		level    VARCHAR(128),
	 *		category VARCHAR(128),
	 *		logtime  INTEGER,
	 *		message  TEXT
	 *   )
	 * </pre>
	 * Note, the 'id' column must be created as an auto-incremental column.
	 * In MySQL, this means it should be <code>id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY</code>;
	 * In PostgreSQL, it is <code>id SERIAL PRIMARY KEY</code>.
	 * @see autoCreateLogTable
	 */
	public $logTableName='YiiLog';
	/**
	 * @var boolean whether the log DB table should be automatically created if not exists. Defaults to true.
	 * @see logTableName
	 */
	public $autoCreateLogTable=true;
	/**
	 * @var CDbConnection the DB connection instance
	 */
	private $_db;

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		if($this->autoCreateLogTable)
		{
			$db=$this->getDbConnection();
			try
			{
				$db->createCommand()->delete($this->logTableName,'0=1');
			}
			catch(Exception $e)
			{
				$this->createLogTable($db,$this->logTableName);
			}
		}
	}

	/**
	 * Creates the DB table for storing log messages.
	 * @param CDbConnection $db the database connection
	 * @param string $tableName the name of the table to be created
	 */
	protected function createLogTable($db,$tableName)
	{
		$db->createCommand()->createTable($tableName, array(
			'id'=>'pk',
			'level'=>'varchar(500)',
			'category'=>'varchar(128)',
			'logtime'=>'varchar(128)',
			'message'=>'text',
		),'ENGINE=InnoDB CHARSET=utf8');
	}

	/**
	 * @return CDbConnection the DB connection instance
	 * @throws CException if {@link connectionID} does not point to a valid application component.
	 */
	protected function getDbConnection()
	{
		if($this->_db!==null)
			return $this->_db;
		elseif(($id=$this->connectionID)!==null)
		{
			if(($this->_db=Yii::app()->getComponent($id)) instanceof CDbConnection)
				return $this->_db;
			else
				throw new CException(Yii::t('yii','CDbLogRoute.connectionID "{id}" does not point to a valid CDbConnection application component.',
					array('{id}'=>$id)));
		}
		else
		{
			$dbFile=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'log-'.Yii::getVersion().'.db';
			return $this->_db=new CDbConnection('sqlite:'.$dbFile);
		}
	}

	/**
	 * Stores log messages into database.
	 * @param array $logs list of log messages
	 */
	protected function processLogs($logs)
	{
		$command=$this->getDbConnection()->createCommand();
		foreach($logs as $log)
		{
			$date_array = explode(" ",(int)$log[3]);
			$date = date("d.m.Y H:i",$date_array[0]);
			if($log[1] == 'error'){
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#eb5f69;">error</span>';
			}elseif ($log[1] == 'warning') {
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#fcb117;">warning</span>';
			}elseif ($log[1] == 'info') {
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#5692bb;">info</span>';
			}elseif ($log[1] == 'created') {
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#6ac28e;">created</span>';
			}elseif ($log[1] == 'updated') {
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#756994;">updated</span>';
			}
			elseif ($log[1] == 'deleted') {
				$level = '<span style="padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#ffffff;vertical-align:baseline;white-space:nowrap;background-color:#999;">deleted</span>';
			}
            $is_console = PHP_SAPI == 'cli'; //if is console app return bool
            if (!$is_console){
                $ip = $this->get_ip();
            }else{
                $ip = "127.0.0.1";
            }

			$command->insert($this->logTableName,array(
				'level'=>$level,
				'category'=>$log[2],
				'logtime'=>$date,
				'message'=>'IP:['.$ip.'] '.$log[0],
			));
		}
	}

	protected function get_ip()
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipa[] = trim(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipa[] = $_SERVER['HTTP_CLIENT_IP'];       
        
        if (isset($_SERVER['REMOTE_ADDR']))
            $ipa[] = $_SERVER['REMOTE_ADDR'];
        
        if (isset($_SERVER['HTTP_X_REAL_IP']))
            $ipa[] = $_SERVER['HTTP_X_REAL_IP'];
        
        // проверяем ip-адреса на валидность начиная с приоритетного.
        foreach($ipa as $ips)
        {
            //  если ip валидный обрываем цикл, назначаем ip адрес и возвращаем его
            if($this->is_valid_ip($ips))
            {                    
                $ip = $ips;
                break;
            }
        }
        return $ip;
    }
    
    /**
     * функция для проверки валидности ip адреса
     * @param ip адрес в формате 1.2.3.4
     * @return bolean : true - если ip валидный, иначе false
     */
    protected function is_valid_ip($ip=null)
    {
        if(preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#", $ip))
            return true; // если ip-адрес попадает под регулярное выражение, возвращаем true
        
        return false; // иначе возвращаем false
    }
}
