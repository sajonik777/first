Cron extensions
===============

Block console commands until executed. 
Unlock cars exhibited at the expiration of the block if the server is down

Config
------

Insert in your commands
-----------------------

```php
public function behaviors()
{
    return CMap::mergeArray(parent::behaviors(), array('LockUnLockBehavior' => array(
                    'class' => 'yiicod\cron\commands\behaviors\LockUnLockBehavior',
                    'timeLock' => 'duration' //Set timeLock
                ))
    );
}
```