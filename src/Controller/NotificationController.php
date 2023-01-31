<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\User;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    private NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function updateNotification(Request $request, USER $user, string $status): void
    {
        $notification = $this->notificationRepository->findOneBy(
            [
                'user' => $user,
                'decision' => $request->request->get($status)
            ]
        );

        $notification->setUserRead(true);
        $this->notificationRepository->save($notification, true);
    }

    public function sumNotification(): Response
    {
        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $this->notificationRepository->sumByUser(HomeController::USERID)]
        );
    }

    #[Route('/notification', name: 'app_notification')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
    ): Response {

       /** @var \App\Entity\User */
        $user = $this->getUser();
        $notifications = $paginator->paginate(
            $this->notificationRepository->findNotification($user->getId()),
            $request->query->getInt('page', 1),
            10
        );

        if (!empty($request->request->all())) {
            switch (key($request->request->all())) {
                case Notification::STATUS_SHOW:
                    // dd($request->request->get(Notification::STATUS_SHOW));
                    $this->updateNotification($request, $user, Notification::STATUS_SHOW);
                    return $this->redirectToRoute('app_give_opinion', [
                        'decision' => $request->request->get(Notification::STATUS_SHOW)
                    ]);
                case Decision::STATUS_CURRENT:
                    $this->updateNotification($request, $user, Decision::STATUS_CURRENT);
                    return $this->redirectToRoute('app_give_opinion', [
                        'decision' => $request->request->get(Decision::STATUS_CURRENT)
                    ]);

                case Decision::STATUS_FIRST_DECISION:
                    $this->updateNotification($request, $user, Decision::STATUS_FIRST_DECISION);
                    return $this->redirectToRoute('app_conflict', [
                        'decision' => $request->request->get(Decision::STATUS_FIRST_DECISION)
                    ]);

                case Decision::STATUS_CONFLICT:
                    $this->updateNotification($request, $user, Decision::STATUS_CONFLICT);
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
