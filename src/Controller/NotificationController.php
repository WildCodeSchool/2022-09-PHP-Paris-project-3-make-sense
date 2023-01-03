<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(
        NotificationRepository $notificationRepository
    ): Response {

        $userId = 200;

        // dd($notificationRepository->findAllNotification($userId));
        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notificationRepository->findAllNotification($userId)
            ]
        );
    }
}
