<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\NotificationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */

class NotificationController extends AbstractController
{
    public const USERID = 51;

    public function notificationSum(NotificationRepository $notificationRep): Response
    {
        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $notificationRep->getTotalByUser(self::USERID)]
        );
    }

    #[Route('/notification', name: 'app_notification')]
    public function index(
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $user = $userRepository->findOneBy(['id' => self::USERID]);

        $notifications = $paginator->paginate(
            $notificationRepository->findNotification(self::USERID),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notifications,
                'user' => $user
            ]
        );
    }
}
