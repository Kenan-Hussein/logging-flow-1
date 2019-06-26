<?php

namespace App\Controller;

use App\Message\LogMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoggingController extends AbstractController
{

    /**
     * @Route("/logging", name="logging")
     */
    public function postLog()
    {
        //message text
        $message = "yah it is working";

        $this->jsonMessage($message);

        $this->messengerMessage($message);
    }

    public function jsonMessage(string $message)
    {
        return new JsonResponse(
            [
                'message' => $message,
            ],
            JsonResponse::HTTP_OK
        );
    }

    public function messengerMessage(string $message)
    {
        // will cause the LogMessageHandler to be called
         //$bus->dispatch(new LogMessage($message));
        $this->dispatchMessage(new LogMessage($message));
    }
}
