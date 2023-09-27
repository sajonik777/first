<?php

use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;

        return true;
    }

    return false;
});

/**
 * Class Asterisk
 */
class Asterisk
{
    /**
     * @param $caller
     * @param $number
     *
     * @return bool
     * @throws \PAMI\Client\Exception\ClientException
     */
    public static function call($caller, $number)
    {
        $config = self::loadConfig();

        if (0 == $config['enabled']) {
            return false;
        }

        $ami = new \PAMI\Client\Impl\ClientImpl($config);
//        $ami->registerEventListener(new A());
        $ami->open();

//        $originate = new \PAMI\Message\Action\OriginateAction("Local/{$caller}@from-office");
        $originate = new \PAMI\Message\Action\OriginateAction((string)$config['channel'] . '/' . $caller);

        $originate->setCallerId((string)$caller);
        $originate->setContext((string)$config['сontext']);
        $originate->setExtension((string)$number);
        $originate->setTimeout((int)$config['read_timeout']);
        $originate->setPriority(1);
        $originate->setAsync(true);

        $message = $ami->send($originate);
        //var_dump($message->getMessage());
        return $message->isSuccess();
    }

    /**
     * @return bool
     */
    public static function isEnabled()
    {
        $config = self::loadConfig();

        return (bool)$config['enabled'];
    }

    /**
     * @return array
     */
    private static function loadConfig()
    {
        $configFile = __DIR__ . '/../config/ami.inc';
        $content = file_get_contents($configFile);
        $options = unserialize(base64_decode($content));
        $return = [];
        $return['host'] = $options['amiHost'];
        $return['scheme'] = $options['amiScheme'];
        $return['port'] = $options['amiPort'];
        $return['username'] = $options['amiUsername'];
        $return['secret'] = $options['amiSecret'];
        $return['connect_timeout'] = $options['amiConnectTimeout'];
        $return['read_timeout'] = $options['amiReadTimeout'];
        $return['enabled'] = $options['amiEnabled'];
        $return['сontext'] = $options['amiContext'];
        $return['channel'] = $options['amiChannel'];

        return $return;
    }
}

class A implements IEventListener
{
    public function handle(EventMessage $event)
    {
        var_dump($event);
    }
}