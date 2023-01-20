<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\UserRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\NotificationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        ExpertiseRepository $expertiseRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $userId = 51;

        $user = $userRepository->findOneBy(['id' => $userId]);

        // dd($notificationRepository->findAllNotification($userId));
        $notifications = $paginator->paginate(
            $notificationRepository->findAllNotification($userId),
            $request->query->getInt('page', 1),
            2
        );

        // dd($notifications);


        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notifications,
                'experts' => $expertiseRepository->countExpertiseByDecision($userId),
                // 'messages' => Notification::NOTIFICATIONS_MESSAGE,
                // 'buttons' => Notification::NOTIFICATIONS_BUTTON,
                'user' => $user
            ]
        );
    }
}
