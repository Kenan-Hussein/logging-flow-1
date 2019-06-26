<?php

namespace App\Controller;

use App\Message\LogMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $this->messengerMessage($message);

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
}
