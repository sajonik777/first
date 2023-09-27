<?php

/**
 * Trait PushTrait
 */
trait PushTrait
{
    /**
     * Отправляет push уведомление пользователю.
     *
     * @param $notification string текст сообщения.
     * @param $url
     * @throws CDbException
     * @throws CException
     */
    public function pushMessage($notification, $url)
    {
        $sql = "SELECT * FROM `subscribers` WHERE `user_id`={$this->id}";
        $notification = addslashes($notification);
        /** @var CDbConnection $connection */
        $connection = Yii::app()->db;
        $row = $connection->createCommand($sql)->queryRow();
        if ($row) {
//            $chrome = $row['chrome'] == 1 ? 'chrome' : 'firefox';
//            if (!PushAPI::send_push_message($chrome, $row['subscriber_id'])) {
            if (!PushAPI::sendPush($notification, $url, $row['subscriber_id'])) {
                return;
            }
            //$sql = "INSERT INTO `pushs` (`user_id`, `notification`, `url`) VALUES ({$this->id}, '{$notification}', '{$url}');";
            //$connection->createCommand($sql)->execute();
        }
    }
}
