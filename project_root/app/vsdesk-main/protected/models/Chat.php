<?php

class Chat
{
    protected $id;
    protected $created;
    protected $name;
    protected $reader;
    protected $message;

    public static function getAllChats($limit = 100)
    {
        $dbconfig = dirname(__FILE__) . '/../config/dbconfig.php';
        $config = require($dbconfig);
        $connectionString = $config['connectionString'] . ';charset=' . $config['charset'];
        $connection = new PDO($connectionString, $config['username'], $config['password']);
        $chats = $connection->query('SELECT * FROM chat WHERE reader IS NULL ORDER BY id ASC LIMIT 100');
        $jsonData = [];
        foreach ($chats->fetchAll(PDO::FETCH_CLASS, 'Chat') as $row) {
            /* @var $row Chat */
            $jsonData[] = [
                'name' => $row->getName(),
                'created' => $row->getCreated(),
                'message' => $row->getMessage()
            ];
        }
        $connection = null;
        unset($dbconfig, $config, $connectionString, $connection, $chats);
        return $jsonData;
    }

    public static function getMyChats($sender, $reader, $limit = 100)
    {
        $dbconfig = dirname(__FILE__) . '/../config/dbconfig.php';
        $config = require($dbconfig);
        $connectionString = $config['connectionString'] . ';charset=' . $config['charset'];
        $connection = new PDO($connectionString, $config['username'], $config['password']);
        if ($reader == 'main'){
            $chats = $connection->query("SELECT * FROM chat WHERE (`reader`='" . $reader . "') ORDER BY id DESC LIMIT 100");
        } else {
            $chats = $connection->query("SELECT * FROM chat WHERE (`reader`='" . $reader . "' AND `name`='" . $sender . "') OR (`reader`='" . $sender . "' AND `name`='" . $reader . "') ORDER BY id DESC LIMIT 100");
        }
        $jsonData = [];
        foreach ($chats->fetchAll(PDO::FETCH_CLASS, 'Chat') as $row) {
            /* @var $row Chat */
            $jsonData[] = [
                'name' => $row->getName(),
                'reader' => $row->getReader(),
                'created' => $row->getCreated(),
                'message' => $row->getMessage()
            ];
        }
        $connection = null;
        unset($dbconfig, $config, $connectionString, $connection, $chats);
        return $jsonData;
    }

    public function save()
    {
        $dbconfig = dirname(__FILE__) . '/../config/dbconfig.php';
        $config = require($dbconfig);
        $connectionString = $config['connectionString'] . ';charset=' . $config['charset'];
        $connection = new PDO($connectionString, $config['username'], $config['password']);
        $chat = $connection->prepare('INSERT INTO chat (`name`, `reader`, `message`) VALUES (:name, :reader, :message)');
        $chat->execute([
            ':name' => $this->getName(),
            ':reader' => $this->getReader(),
            ':message' => $this->getMessage(),
        ]);
        $ret = $chat->rowCount();
        $connection = null;
        unset($dbconfig, $config, $connectionString, $connection, $chat);
        return $ret;
    }

    public function getJson()
    {
        $jsonData = [
            'created' => $this->getCreated(),
            'name' => $this->getName(),
            'reader' => $this->getReader(),
            'message' => $this->getMessage(),
        ];
        return $jsonData;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCreated()
    {
        if (!empty($this->created))
            return date('d.m.Y H:i', strtotime($this->created));
        else
            return date('d.m.Y H:i');
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getReader()
    {
        return $this->reader;
    }

    public function setReader($reader)
    {
        $this->reader = $reader;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
