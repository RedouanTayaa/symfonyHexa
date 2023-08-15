<?php

namespace App\Controller;

use App\libs\messaging\application\usescases\PostMessageUseCase;
use App\libs\messaging\domain\entity\Message;
use App\libs\messaging\infra\DateProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message', methods: 'POST')]
    public function addMessage(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $jsonRequest = json_decode($request->getContent(), true);
        $messageRepository = $entityManager->getRepository(Message::class);
        $dateProvider = new DateProvider();
        $postMessageUseCase = new PostMessageUseCase($messageRepository, $dateProvider);
        $messageCommand = $jsonRequest['message'];

        $postMessageUseCase->handle($jsonRequest['message']);

        return $this->json(null, 201);
    }
}
