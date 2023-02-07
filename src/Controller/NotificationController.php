<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Notification;
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
    private NotificationRepository $notificationRepository;

    public function __construct(
        NotificationRepository $notificationRepository,
    ) {
        $this->notificationRepository = $notificationRepository;
    }

    public function updateNotification(Request $request, string $status): void
    {
        $notification = $this->notificationRepository->findOneBy(
            [
                'user' => $this->getUser(),
                'decision' => $request->request->get($status)
            ]
        );

        $notification->setUserRead(true);
        $this->notificationRepository->save($notification, true);
    }

    public function sumNotification(): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        return $this->render(
            'partials/_notification.html.twig',
            ['notifications' => $this->notificationRepository->sumByUser($user->getId())]
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
            8
        );

        if (!empty($request->request->all())) {
            switch (key($request->request->all())) {
                case Notification::STATUS_SHOW:
                    //  $this->updateNotification($request, Notification::STATUS_SHOW);
                    return $this->redirectToRoute('app_decision_show', [
                        'decision_id' => $request->request->get(Notification::STATUS_SHOW),
                    ]);
                case Decision::STATUS_CURRENT:
                    // $this->updateNotification($request, Decision::STATUS_CURRENT);
                    return $this->redirectToRoute('app_decision_give_opinion', [
                        'decision_id' => $request->request->get(Decision::STATUS_CURRENT),
                        'opinionState' => 'like'
                    ]);

                case Decision::STATUS_FIRST_DECISION:
                    // $this->updateNotification($request, Decision::STATUS_FIRST_DECISION);
                    return $this->redirectToRoute('app_decision_firstdecision', [
                        'decision_id' => $request->request->get(Decision::STATUS_FIRST_DECISION)
                    ]);

                case Decision::STATUS_CONFLICT:
                    // $this->updateNotification($request, Decision::STATUS_CONFLICT);
                    return $this->redirectToRoute('app_decision_validation', [
                        'decision_id' => $request->request->get(Decision::STATUS_CONFLICT),
                        // 'state' => 'like'
                    ]);

                default:
                    break;
            }
        }

        return $this->render(
            'notification/index.html.twig',
            [
                'notifications' => $notifications
            ]
        );
    }
}
