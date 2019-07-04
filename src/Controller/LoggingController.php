<?php

namespace App\Controller;

use App\Message\LogMessage;
use Kafka\Producer;
use Kafka\ProducerConfig;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LoggingController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/logging", name="logging")
     */
    public function postLog()
    {
        //message text
        $message = "yah it is working";

        //Jobs:
        //Send message to Kafka
        $this->sendToKafka();
        //Send message using messenger
        //$this->messengerMessage($message);
        //Send message to log file
        //$this->elkLogger($message);
        //Return json message with status
        return $this->jsonMessage($message);
    }

    public function jsonMessage(string $message)
    {
        return new JsonResponse(
            [
                'message' => $message,
            ],
            JsonResponse::HTTP_OK //since they advised to use this instead of the number
        );
    }

    public function messengerMessage(string $message)
    {
        // will cause the LogMessageHandler to be called
        $this->dispatchMessage(new LogMessage($message));
    }

    public function elkLogger($message)
    {
        $this->logger->info($message);
    }

    public function sendToKafka()
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList('127.0.0.1:9092');
        $config->setBrokerVersion('1.0.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);

        $producer = new Producer(function () {
            return [
                [
                    'topic' => 'ke',
                    'value' => 'Yes-Soft......message',
                    'key' => '',
                ],
            ];
        });

        $producer->success(function ($result): void {
            var_dump($result);
        });

        $producer->error(function ($errorCode): void {
            var_dump($errorCode);
        });

        $producer->send(true);
    }
}
