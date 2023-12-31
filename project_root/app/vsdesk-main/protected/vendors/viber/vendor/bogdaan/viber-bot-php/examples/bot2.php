<?php

/**
 * Before you run this example:
 * 1. install monolog/monolog: composer require monolog/monolog
 * 2. copy config.php.dist to config.php: cp config.php.dist config.php
 *
 * @author Novikov Bogdan <hcbogdan@gmail.com>
 */

require_once("../vendor/autoload.php");

use Viber\Bot;
use Viber\Api\Sender;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$config = require('./config.php');
$apiKey = $config['apiKey'];

// reply name
$botSender = new Sender([
    'name' => 'Univef bot',
    'avatar' => 'https://developers.viber.com/images/favicon.ico',
]);

// log bot interaction
$log = new Logger('bot');
$log->pushHandler(new StreamHandler('/tmp/bot.log'));

$bot = null;

try {
    // create bot instance
    $bot = new Bot(['token' => $apiKey]);
    $bot
        ->onConversation(function ($event) use ($bot, $botSender, $log) {
            $log->info('onConversation ' . var_export($event, true));
            $textMessage = new \Viber\Api\Message\Text();
            return $textMessage
                ->setSender($botSender)
                ->setText('Приветственный текст!');
        })
        ->onText('|whois .*|si', function ($event) use ($bot, $botSender, $log) {
            $log->info('onText whois ' . var_export($event, true));
            // match by template, for example "whois Bogdaan"
            $bot->getClient()->sendMessage(
                (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($event->getSender()->getId())
                    ->setText("I do not know )")
            );
        })
        ->onText('|.*|s', function ($event) use ($bot, $botSender, $log) {
            $log->info('onText ' . var_export($event, true));
            // .* - match any symbols
            $bot->getClient()->sendMessage(
                (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($event->getSender()->getId())
                    ->setText("HI!")
            );
        })
        ->on(function ($event) {
            return true; // match all
        }, function ($event) use ($bot, $botSender, $log) {

            if (
                $event instanceof \Viber\Api\Event\Message
                && $event->getMessage() instanceof \Viber\Api\Message\Picture
            ) {
                $bot->getClient()->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($event->getSender()->getId())
                        ->setText($event->getMessage()->getMedia())
                );
            }


            $log->info('Other event: ' . var_export($event, true));
        })
        ->run();
} catch (Exception $e) {
    $log->warning('Exception: ', $e->getMessage());
    if ($bot) {
        $log->warning('Actual sign: ' . $bot->getSignHeaderValue());
        $log->warning('Actual body: ' . $bot->getInputBody());
    }
}
