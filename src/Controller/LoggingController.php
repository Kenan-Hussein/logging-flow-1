<?php

namespace App\Controller;

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
        return new JsonResponse(
            [
                'message' => 'yeaaah it is working',
            ],
            JsonResponse::HTTP_OK
        );
    }
}
