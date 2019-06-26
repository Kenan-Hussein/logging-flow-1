<?php


namespace App\MessageHandler;

use App\Message\LogMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class LogMessageHandler implements MessageHandlerInterface
{
    public function __invoke(LogMessage $message)
    {
        //do some work - like print message!
        $output = $message->getContent();
        echo $output;
    }
}