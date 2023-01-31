<?php

namespace App\Controller;

use App\Entity\Decision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\NotificationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */

class NotificationController extends AbstractController
{
    public function sumNotification(NotificationRepository $notificationRep): Response
    {
        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $notificationRep->sumByUser(HomeController::USERID)]
        );
    }

    #[Route('/notification', name: 'app_notification')]
    public function index(
        Request $request,
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator,
    ): Response {

        $user = $userRepository->findOneBy(['id' => HomeController::USERID]);

        $notifications = $paginator->paginate(
            $notificationRepository->findNotification(HomeController::USERID),
            $request->query->getInt('page', 1),
            5
        );

        if (!empty($request->request->all())) {
            switch (key($request->request->all())) {
                case Decision::STATUS_CURRENT:
                    $notification = $notificationRepository->findOneBy(
                        [
                            'user' => $user,
                            'decision' => $request->request->get(Decision::STATUS_CURRENT)
                        ]
                    );

                    $notification->setUserRead(true);
                    $notificationRepository->save($notification, true);

                    return $this->redirectToRoute('app_give_opinion', [
                        'decision' => $request->request->get(Decision::STATUS_CURRENT)
                    ]);

                case Decision::STATUS_FIRST_DECISION:
                    $notification = $notificationRepository->findOneBy(
                        [
                            'user' => $user,
                            'decision' => $request->request->get(Decision::STATUS_FIRST_DECISION)
                        ]
                    );

                    $notification->setUserRead(true);
                    $notificationRepository->save($notification, true);

                    return $this->redirectToRoute('app_conflict', [
                        'decision' => $request->request->get(Decision::STATUS_FIRST_DECISION)
                    ]);

                case Decision::STATUS_CONFLICT:
                    $notification = $notificationRepository->findOneBy(
                        [
                            'user' => $user,
                            'decision' => $request->request->get(Decision::STATUS_CONFLICT)
                        ]
                    );

                    $notification->setUserRead(true);
                    $notificationRepository->save($notification, true);

                    return $this->redirectToRoute('app_validation', [
                        'decision' => $request->request->get(Decision::STATUS_CONFLICT)
                    ]);

                default:
                    break;
            }
        }

        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notifications,
                'user' => $user
            ]
        );
    }
}
