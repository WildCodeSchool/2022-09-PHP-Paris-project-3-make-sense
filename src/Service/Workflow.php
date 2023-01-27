<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\History;
use App\Entity\Decision;
use App\Entity\Notification;
use App\Repository\HistoryRepository;
use App\Repository\NotificationRepository;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */

class Workflow
{
    private HistoryRepository $historyRepository;
    private NotificationRepository $notificationRepository;

    public function __construct(
        HistoryRepository $historyRepository,
        NotificationRepository $notificationRepository
    ) {
        $this->historyRepository = $historyRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function addHistory(Decision $decision): void
    {
        $history = new History();
        $history->setStatus($decision->getStatus());
        $history->setStartedAt($decision->getCreatedAt());
        $history->setEndedAt($decision->getEndAt());
        $history->setDecision($decision);

        $this->historyRepository->save($history, true);
    }

    public function addNotification(Decision $decision, User $user): void
    {
        $notification = $this->notificationRepository->findOneBy(
            [
                'user' => $user,
                'decision' => $decision
            ]
        );

        if (is_null($notification)) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setDecision($decision);
            $this->notificationRepository->save($notification, true);
        }
    }
}
